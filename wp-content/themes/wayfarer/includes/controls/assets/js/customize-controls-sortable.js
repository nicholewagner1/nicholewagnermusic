/*global _:false, Backbone:false, wp:false */

(function( window, $, _, Backbone, wp, undefined ) {
	'use strict';

	var api = wp.customize,
		app = {};

	_.extend( app, { model: {}, view: {} } );

	/**
	 * ========================================================================
	 * MODELS
	 * ========================================================================
	 */

	app.model.Choice = Backbone.Model.extend({
		defaults: {
			title: '',
			order: 0
		}
	});

	app.model.Choices = Backbone.Collection.extend({
		model: app.model.Choice,

		comparator: function( choice ) {
			return parseInt( choice.get( 'order' ), 10 );
		}
	});

	/**
	 * ========================================================================
	 * VIEWS
	 * ========================================================================
	 */

	app.view.CustomizerControl = wp.Backbone.View.extend({
		initialize: function( options ) {
			this.setting = options.setting;

			this.listenTo( this.collection, 'sort', this.updateSetting );
		},

		render: function() {
			this.views.add([
				new app.view.SortableList({
					collection: this.collection,
					parent: this
				}),
			]);

			return this;
		},

		updateSetting: function() {
			var itemIds = this.collection.sort({ silent: true }).pluck( 'id' ).join( ',' );
			this.setting.set( itemIds );
		}
	});

	app.view.SortableList = wp.Backbone.View.extend({
		className: 'wayfarer-sortable-list',
		tagName: 'ol',

		render: function() {
			this.$el.empty();
			this.collection.each( this.addChoice, this );
			this.initializeSortable();
			return this;
		},

		addChoice: function( choice ) {
			var choiceView = new app.view.Choice({ model: choice });
			this.$el.append( choiceView.render().el );
		},

		initializeSortable: function() {
			this.$el.sortable({
				axis: 'y',
				delay: 150,
				forceHelperSize: true,
				forcePlaceholderSize: true,
				opacity: 0.6,
				start: function( e, ui ) {
					ui.placeholder.css( 'visibility', 'visible' );
				},
				update: _.bind( this.updateOrder, this )
			})
		},

		updateOrder: function() {
			_.each( this.$el.find( 'li' ), function( choice, i ) {
				var id = $( choice ).data( 'choice-id' );
				this.collection.get( id ).set( 'order', i );
			}, this );

			this.collection.sort();
		}
	});

	app.view.Choice = wp.Backbone.View.extend({
		tagName: 'li',
		className: 'wayfarer-sortable-item',
		template: wp.template( 'wayfarer-sortable-item' ),

		render: function() {
			this.$el.html( this.template( this.model.toJSON() ) ).data( 'choice-id', this.model.get( 'id' ) );
			return this;
		},
	});

	/**
	 * ========================================================================
	 * SETUP
	 * ========================================================================
	 */

	api.controlConstructor['wayfarer-sortable'] = api.Control.extend({
		ready: function() {
			this.choices = new app.model.Choices( this.params.choices );
			delete this.params.choices;

			this.view = new app.view.CustomizerControl({
				el: this.container,
				collection: this.choices,
				data: this.params,
				setting: this.setting
			});

			this.view.render();
		}
	});

})( window, jQuery, _, Backbone, wp );
