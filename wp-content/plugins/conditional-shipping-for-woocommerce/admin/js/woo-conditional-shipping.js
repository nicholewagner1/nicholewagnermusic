jQuery(document).ready(function($) {
	var wcsConditionsTable = {
		operators: [],
		conditions: [],
		triggersInit: false,
		table: null,

		init: function() {
			var table = $( 'table.woo-conditional-shipping-conditions' );

			if ( table.length == 0 ) {
				return;
			}

			this.table = table;

			this.operators = table.data( 'operators' );
			this.conditions = table.data( 'conditions' );

			this.initTagSearch();
			this.initCouponSearch();
			this.insertExisting();
			this.insertEmpty();

			if ( ! this.triggersInit ) {
				this.triggerFieldUpdates();
				this.triggerRemoveCondition();
				this.triggerAddCondition();
				this.triggerToggleValueInputs();

				this.triggersInit = true;
			}
		},

		/**
		 * Show correct fields when changing condition type
		 */
		triggerFieldUpdates: function() {
			var self = this;

			$( document ).on( 'change', 'select.wcs_condition_type_select', function() {
				var row = $( this ).closest( 'tr' );

				self.toggleOperators( row );
				self.toggleValueInputs( row );
			});
		},

		/**
		 * Insert existing conditions into the table
		 */
		insertExisting: function() {
			for ( var i = 0; i < this.conditions.length; i++ ) {
				this.addCondition( this.conditions[i] );
			}

			$( document.body ).trigger( 'wc-enhanced-select-init' );

			this.toggleAllValueInputs();
		},

		/**
		 * Insert empty condition
		 */
		insertEmpty: function() {
			if ( $( 'tbody tr', this.table ).length == 0 ) {
				this.addCondition( {} );
			}
		},

		/**
		 * Toggle all value inputs
		 */
		toggleAllValueInputs: function() {
			var self = this;

			$( 'tbody tr', this.table ).each( function() {
				self.toggleOperators( $( this ) );
				self.toggleValueInputs( $( this ) );
			});
		},

		/**
		 * Toggle value inputs for a single row
		 */
		toggleValueInputs: function( row ) {
			this.removeClassStartingWith( row, 'wcs-operator-' );
			this.removeClassStartingWith( row, 'wcs-type-' );

			var type = $( 'select.wcs_condition_type_select', row ).val();
			var operator = $( 'select.wcs_operator_select', row ).val();

			row.addClass( 'wcs-operator-' + operator );
			row.addClass( 'wcs-type-' + type );

			$( '.wcs-values select:data(placeholder)', row ).trigger( 'change' );
		},

		/**
		 * Toggle operators
		 */
		toggleOperators: function( row ) {
			var operators = $( 'select.wcs_condition_type_select option:selected', row) .data( 'operators' );

			// Save current value
			var currentValue = $( 'select.wcs_operator_select', row ).val();

			// First remove all operators
			$( 'select.wcs_operator_select option', row ).remove();

			var self = this;
			$.each( operators, function( index, value ) {
				self.renderOperator( row, value );
			} );

			if ( typeof currentValue != 'undefined' ) {
				if ( $( 'select.wcs_operator_select option[value="' + currentValue + '"]', row ).length > 0 ) {
					$( 'select.wcs_operator_select', row ).val( currentValue ).trigger( 'change' );
				}
			}
		},

		/**
		 * Render operator
		 */
		renderOperator: function( row, operator ) {
			var operatorTitle = this.operators[operator];

			$( 'select.wcs_operator_select', row ).append( '<option value="' + operator + '">' + operatorTitle + '</option>' );
		},

		/**
		 * Add new condition
		 */
		addCondition: function( data ) {
			// Get index
			var index = this.table.data( 'index' );
			if (typeof index == 'undefined') { index = 0; }
			data['index'] = index;

			// Add one to conditions table index
			this.table.data( 'index', index + 1 );

			// Get template
			var row_template = wp.template( 'wcs_row_template' );

			// Add products
			var products_data = this.table.data( 'selected-products' );
			data.selected_products = [];
			if ( typeof data.product_ids !== 'undefined' && data.product_ids !== null && data.product_ids.length > 0 ) {
				jQuery.each( data.product_ids, function( index, product_id ) {
					if ( typeof products_data[product_id] !== 'undefined' ) {
						data.selected_products.push({
							'id': product_id,
							'title': products_data[product_id]
						});
					}
				});
			}

			// Add tags
			var tags_data = this.table.data( 'selected-tags' );
			data.selected_tags = [];
			if ( typeof data.product_tags !== 'undefined' && data.product_tags !== null && data.product_tags.length > 0 ) {
				jQuery.each( data.product_tags, function( index, tag_id ) {
					if ( typeof tags_data[tag_id] !== 'undefined' ) {
						data.selected_tags.push({
							'id': tag_id,
							'title': tags_data[tag_id]
						});
					}
				});
			}

			// Add coupons
			var coupons_data = this.table.data( 'selected-coupons' );
			data.selected_coupons = [];
			if ( typeof data.coupon_ids !== 'undefined' && data.coupon_ids !== null && data.coupon_ids.length > 0 ) {
				jQuery.each( data.coupon_ids, function( index, coupon_id ) {
					if ( typeof coupons_data[coupon_id] !== 'undefined' ) {
						data.selected_coupons.push({
							'id': coupon_id,
							'title': coupons_data[coupon_id]
						});
					}
				});
			}

			// Render template and add to the table
			$( 'tbody', this.table ).append( row_template( data ) );

			$( document.body ).trigger( 'wc-enhanced-select-init' );

			this.toggleAllValueInputs();
		},

		/**
		 * Tag search
		 */
		initTagSearch: function() {
			$( document.body ).on( 'wc-enhanced-select-init', function() {
				$( ':input.wcs-tag-search' ).filter( ':not(.enhanced)' ).each( function() {
					var select2_args = {
						allowClear        : $( this ).data( 'allow_clear' ) ? true : false,
						placeholder       : $( this ).data( 'placeholder' ),
						minimumInputLength: $( this ).data( 'minimum_input_length' ) ? $( this ).data( 'minimum_input_length' ) : 3,
						escapeMarkup      : function( m ) {
							return m;
						},
						ajax: {
							url: wc_enhanced_select_params.ajax_url,
							dataType: 'json',
							delay: 250,
							data: function( params ) {
								return {
									term: params.term,
									action: 'wcs_json_search_tags',
								};
							},
							processResults: function( data ) {
								var terms = [];
								if ( data ) {
									$.each( data, function( id, term ) {
										terms.push({
											id: term.term_id,
											text: term.name
										});
									});
								}
								return {
									results: terms
								};
							},
							cache: true
						}
					};

					$( this ).selectWoo( select2_args ).addClass( 'enhanced' );
				});
			} );
		},

		/**
		 * Coupon search
		 */
		 initCouponSearch: function() {
			$( document.body ).on( 'wc-enhanced-select-init', function() {
				$( ':input.wcs-coupon-search' ).filter( ':not(.enhanced)' ).each( function() {
					var select2_args = {
						allowClear        : $( this ).data( 'allow_clear' ) ? true : false,
						placeholder       : $( this ).data( 'placeholder' ),
						minimumInputLength: 1,
						escapeMarkup      : function( m ) {
							return m;
						},
						ajax: {
							url: wc_enhanced_select_params.ajax_url,
							dataType: 'json',
							delay: 250,
							data: function( params ) {
								return {
									coupon: params.term,
									action: 'wcs_json_search_coupons',
								};
							},
							processResults: function( data ) {
								var coupons = [];
								if ( data ) {
									$.each( data, function( id, coupon ) {
										coupons.push({
											id: coupon.id,
											text: coupon.code
										});
									});
								}
								return {
									results: coupons
								};
							},
							cache: true
						}
					};

					$( this ).selectWoo( select2_args ).addClass( 'enhanced' );
				});
			} );
		},

		/**
		 * Remove selected conditions when clicking the button
		 */
		triggerRemoveCondition: function() {
			var self = this;

			$( document ).on( 'click', 'a.wcs-remove-condition', function( e ) {
				e.preventDefault();
				
				$( this ).closest( 'tr' ).remove();
				self.insertEmpty();
			});
		},

		/**
		 * Add new condition when clicking the Add button
		 */
		triggerAddCondition: function() {
			var self = this;

			$( document ).on( 'click', 'button#wcs-add-condition', function() {
				self.addCondition( {} );
			});
		},

		/**
		 * Update value inputs when changing operator type
		 */
		triggerToggleValueInputs: function() {
			var self = this;

			$( document ).on('change', 'select.wcs_operator_select', function() {
				var row = $( this ).closest( 'tr' );
				self.toggleValueInputs( row );
			});
		},

		removeClassStartingWith: function(el, filter) {
			el.removeClass(function (index, className) {
				return (className.match(new RegExp("\\S*" + filter + "\\S*", 'g')) || []).join(' ');
			});
		}
	};
	wcsConditionsTable.init();

	var wcsActionsTable = {
		actions: [],
		triggersInit: false,
		table: null,

		init: function() {
			var table = $( 'table.woo-conditional-shipping-actions' );

			if ( table.length == 0 ) {
				return;
			}

			this.table = table;

			this.actions = table.data( 'actions' );

			this.insertExisting();
			this.insertEmpty();

			if ( ! this.triggersInit ) {
				this.triggerFieldUpdates();
				this.triggerAddAction();
				this.triggerRemoveAction();
				this.triggerToggleMatchByName();

				this.triggersInit = true;
			}
		},

		/**
		 * Show correct fields when changing action type
		 */
		triggerFieldUpdates: function() {
			var self = this;

			$( document ).on( 'change', 'select.wcs_action_type_select', function() {
				var row = $( this ).closest( 'tr' );

				self.toggleValueInputs( row );
			});
		},

		/**
		 * Insert existing actions into the table
		 */
		insertExisting: function() {
			for ( var i = 0; i < this.actions.length; i++ ) {
				this.addAction( this.actions[i] );
			}

			$( document.body ).trigger( 'wc-enhanced-select-init' );

			this.toggleAllValueInputs();
		},

		/**
		 * Insert empty condition
		 */
		insertEmpty: function() {
			if ( $( 'tbody tr', this.table ).length == 0 ) {
				this.addAction( {} );
			}
		},

		/**
		 * Toggle all value inputs
		 */
		toggleAllValueInputs: function() {
			var self = this;

			$( 'tbody tr', this.table ).each( function() {
				self.toggleValueInputs( $( this ) );
				self.toggleMatchByName( $( this ) );
			});
		},

		/**
		 * Toggle value inputs for a single row
		 */
		toggleValueInputs: function( row ) {
			this.removeClassStartingWith( row, 'wcs-action-type-' );

			var type = $( 'select.wcs_action_type_select', row ).val();

			row.addClass( 'wcs-action-type-' + type );

			$( '.wcs-methods select:data(placeholder)', row ).trigger( 'change' );
		},

		/**
		 * Trigger toggle match by name
		 */
		triggerToggleMatchByName: function() {
			var self = this;

			$( document ).on( 'change', '.wcs-methods select', function( e ) {
				var row = $( this ).closest( 'tr' );

				self.toggleMatchByName( row );
			} );
		},

		/**
		 * Toggle match by name
		 */
		toggleMatchByName: function( row ) {
			var methods = $( '.wcs-methods select', row ).val();
			var nameMatch = $.inArray( '_name_match', methods ) !== -1;

			$( '.wcs-match-by-name', row ).toggle( nameMatch );
		},

		/**
		 * Add action
		 */
		addAction: function( data ) {
			// Get index
			var index = this.table.data( 'index' );
			if (typeof index == 'undefined') { index = 0; }
			data['index'] = index;

			// Add one to conditions table index
			this.table.data( 'index', index + 1 );

			// Get template
			var row_template = wp.template( 'wcs_action_row_template' );

			// Render template and add to the table
			$( 'tbody', this.table ).append( row_template( data ) );

			$( document.body ).trigger( 'wc-enhanced-select-init' );

			this.toggleAllValueInputs();
		},

		/**
		 * Remove selected actions when clicking the button
		 */
		triggerRemoveAction: function() {
			var self = this;

			$( document ).on( 'click', 'a.wcs-remove-action', function( e ) {
				e.preventDefault();
				
				$( this ).closest( 'tr' ).remove();
				self.insertEmpty();
			});
		},

		/**
		 * Add new action when clicking the Add button
		 */
		triggerAddAction: function() {
			var self = this;

			$( document ).on( 'click', 'button#wcs-add-action', function() {
				self.addAction( {} );
			});
		},

		removeClassStartingWith: function(el, filter) {
			el.removeClass(function (index, className) {
				return (className.match(new RegExp("\\S*" + filter + "\\S*", 'g')) || []).join(' ');
			});
		}
	};
	wcsActionsTable.init();

	/**
	 * Toggle Pro features
	 */
	$( 'input[name="wcs_pro_features"]' ).change( function( e ) {
		var displayFeatures = $( this ).is( ':checked' );

		$( '.wcs-table .wcs-condition option:disabled').toggle( displayFeatures );
		$( '.wcs-table .wcs-action option:disabled').toggle( displayFeatures );

		$( '.wcs-table .wcs-condition optgroup' ).each( function() {
			var visibleOptions = $( 'option:not(:disabled)', this ).length;

			$( this ).toggle( ( visibleOptions > 0 || displayFeatures ) );
		} );
	} );
	$( 'input[name="wcs_pro_features"]' ).trigger( 'change' );

	/**
	 * Sortable rulesets
	 */
	$( 'table.wcs-rulesets tbody' ).sortable( {
		items: 'tr',
		cursor: 'move',
		axis: 'y',
		handle: 'td.wcs-ruleset-sort',
		scrollSensitivity: 40
	} );

	/**
	 * Warn when deleting ruleset
	 */
	$( document ).on( 'click', '.wcs-ruleset-delete', function( e ) {
		return confirm( "Are you sure?" );
	} );

	/**
	 * Open health check issue
	 */
	$( document ).on( 'click', '.woo-conditional-shipping-health-check .issue-container .title', function( e ) {
		var container = $( this ).closest( '.issue-container' );

		$( '.details', container ).slideToggle();
		$( '.toggle-indicator' ).toggleClass( 'open' );
	} );

	/**
	 * AJAX toggle for rulesets
	 */
	$( document ).on( 'click', '.wcs-ruleset-status .woocommerce-input-toggle', function( e ) {
		e.preventDefault();

		var self = this;

		var data = {
			id: $( this ).data( 'id' ),
			security: woo_conditional_shipping.nonces.ruleset_toggle
		};

		$.ajax( {
			type: 'post',
			url: woo_conditional_shipping.ajax_url,
			data: data,
			dataType: 'json',
			beforeSend: function() {
				$( self ).removeClass( 'woocommerce-input-toggle--enabled woocommerce-input-toggle--disabled' );
				$( self ).addClass( 'woocommerce-input-toggle--loading' );
			},
			success: function( response ) {
				$( self ).removeClass( 'woocommerce-input-toggle--loading' );

				if ( response.enabled ) {
					var cssClass = 'woocommerce-input-toggle--enabled';
				} else {
					var cssClass = 'woocommerce-input-toggle--disabled';
				}

				$( self ).addClass( cssClass );
			},
			error: function() {
				alert( 'Unknown error' );
			},
			complete: function() {

			}
		} );
	} );
});
