(function ($) {
	$(document).ready(function ($) {

		//simple function to check if the value of field is null is so set it to empty
		function checkNull(value) {
			if (!value) {
				value = "N/A";
			}
			return value;
		};

		/*Section to handle $ functionalities for Terms and condition part of plugin */

		/**
		 * This section will included requred code to process ajax request
		 * 
		 * 
		 */

		//copy business name as e-commerce name
		$(document).on('click', '#ctcSameAsBusinessName', function () {
			var businessName = $("[name*='ctcBusinessName']").val();
			var oldEcommerceName = $("[name*='ctcOldEcommerceName']").val();

			if ($(this).is(':checked')) {
				$("[name*='ctcEcommerceName']").removeData().attr("value", businessName);
			} else {
				$("[name*='ctcEcommerceName']").val(oldEcommerceName);
			}
		});

		//this section will make ajax request to create business page
		$(document).on('click', '#ctcBusinessSettingsButton', function () {
			var oldEcommerceName = $("[name*='ctcOldEcommerceName']").val();
			var eCommerceName = $("[name*='ctcEcommerceName']").val();
			if (eCommerceName != '') {
				var data = {
					'action': 'ctcCreateBusinessPage',
					'eCommerceTitle': eCommerceName,
					'oldEcommerceTitle': oldEcommerceName
				};

				$.post(ajaxurl, data, function (response) { }).fail(function () {
					alert(ctcTrans.ajaxFail);
				});
			} else {

				$("[name*='ctcEcommerceName']").css('border', '2px solid red').attr("placeholder", ctcTrans.ecommerceNameEmpty);
				return false;
			}

		});

		//this section will carry out ajax to register product category
		$(document).on('submit', '#ctcAddProductCategoryForm', function (event) {
			var categoryData = [];
			//if required field category name is empty
			if (!$("[name*='categoryName']").val()) {
				$("[name*='categoryName']").css('border', '2px solid red').attr("placeholder", "Category name cannot be empty.");
				//scroll to the empty element
				$([document.documentElement, document.body]).animate({
					scrollTop: $("[name*='categoryName']").offset().top
				}, 500);
			} else {
				$("[name*='categoryName']").removeAttr("placeholder style");
				var categoryData = $('#ctcAddProductCategoryForm').serializeArray();
				var data = {
					'action': 'ctcAddProductCategory',
					'categoryInfo': JSON.stringify(categoryData)
				};

				// make ajax call
				$.post(ajaxurl, data, function (response) {
					if (response == 1) {
						alert(ctcTrans.productAdded);
						document.getElementById("ctcAddProductCategoryForm").reset();
					} else {
						alert(ctcTrans.issueProductAdded);
					}
				}).fail(function () {
					alert(ctcTrans.ajaxFail);
				});
			}
			event.preventDefault();
			return false;
		});




		//this section provides admin form to update category
		$(document).on('click', '#ctcUpdateProductCategory', function () {
			let data = {
				'action': 'ctcGetCategoryUpdateForm',
				'categoryId': $(this).attr('data-type-id')
			};
			$.ctcOverlayEl({
				elemHeight: '450px',
				elemWidth: '490px',
				ajaxUrl: ajaxurl,
				ajaxData: data,
				ajaxMethod: 'post'
			});






		});



		//this part updates category with ajax
		$(document).on('submit', '#ctcUpdateCategoryForm', function (event) {
			var categoryData = [];
			//if required field category name is empty
			if (!$("[name*='categoryName']").val()) {
				$("[name*='categoryName']").css('border', '2px solid red').attr("placeholder", "Category name cannot be empty.");
				//scroll to the element
				$([document.documentElement, document.body]).animate({
					scrollTop: $("[name*='categoryName']").offset().top
				}, 2000);
			} else {
				var categoryData = $('#ctcUpdateCategoryForm').serializeArray();
				var data = {
					'action': 'ctcUpdateProductCategory',
					'categoryInfo': JSON.stringify(categoryData)
				};

				//trigger ajax to update category
				$.post(ajaxurl, data, function (response) {
					if (response == 1) {
						alert(ctcTrans.categoryUpdated);
						for (var i in categoryData) {
							var categoryId = $('#ctcProductCategoryId').val();
							var tdSelector = categoryId + '-' + categoryData[i]['name'];
							$('.' + tdSelector).empty().append(categoryData[i]['value']);
						}
						//trigger thickbox close botton click
						$('#ctcOverlayElClosebtn').trigger('click');
					} else {
						alert(ctcTrans.issueCategoryUpdated);
					}
				}).fail(function () {
					alert(ctcTrans.ajaxFail);
				});
			}
			event.preventDefault();
			return false;
		});

		//this section deals with deletion of the category with ajax
		$(document).on('click', '#ctcDeleteCategoryButton', function () {
			var categoryId = $('#ctcProductCategoryId').val();
			var deleteConfirm = confirm(ctcTrans.confirmCatDelete);

			if (deleteConfirm == true) {
				var data = {
					'action': 'ctcDeleteProductCategory',
					'categoryId': categoryId
				};

				//trigger ajax to delete category
				$.post(ajaxurl, data, function (response) {
					if (response == 1) {
						$('#ctcOverlayElClosebtn').trigger('click');
						alert(ctcTrans.categoryDeleted);
						$('.' + categoryId + '-categoryName').closest("tr").remove();
					} else {
						alert(ctcTrans.issueCategoryDelete);
					}
				}).fail(function () {
					alert(ctcTrans.ajaxFail);
				});
			};
			return false;
		});


		//this section gets the list of category to add product with ajax
		$(document).on('change', '#ctcProductCategorySelect', function () {
			var id = $("#ctcProductCategorySelect option:selected").attr('data-type-id');
			if (!id) {
				$('#ctcProductSubCategory1, #ctcProductSubCategory2, #ctcProductSubCategory3, #ctcProductSubCategory3, #ctcProductInventory, #ctcAvilableProducts').each(function () {
					if ($(this).is('select')) {
						$(this).empty();
					} else {
						$(this).val('');
					}
				});
				return false;
			} else {
				var data = {
					'action': 'ctcGetSubCategoriesList',
					'categoryId': id
				};
				$.post(ajaxurl, data, function (response) {
					responseObj = JSON.parse(response);
					for (var key in responseObj) {
						$('#ctcProductInventory, #ctcAvilableProducts').val('');
						$("[name*='" + key + "']").empty().append(responseObj[key]);
					}
				}).fail(function () {
					alert(ctcTrans.ajaxFail)
				});
			}
		});


		/**
		 * 
		 * 
		 * 
		 * this section deals with frontend validation of add product form 
		 * 
		 * 
		 * 
		 * 
		 * 
		 */

		//validate the input field for add product form
		$(document).on('click', "#ctcAddAvilableProduct", function () {
			if (!$('#ctcProductName').val()) {
				$('#ctcProductName').attr('placeholder', ctcTrans.productNameEmpty).css({
					'border': '2px solid red'
				});
				return false;
			} else {
				$('#ctcProductName').removeAttr('style');

			}
			var productInventory = $('#ctcProductInventory').val();
			//get the value of selected option 
			var mainCat = $("#ctcProductCategorySelect").val();
			//if the main category is not selected
			if (!mainCat) {
				$("#ctcProductCategorySelect").css('border', '2px solid red');
				alert(ctcTrans.selectValidCategory);
				return false;
			} else {
				$("#ctcProductCategorySelect").removeAttr('style');

			}

			var subCat1 = $("#ctcProductSubCategory1").val();
			var subCat2 = $("#ctcProductSubCategory2").val();
			var subCat3 = $("#ctcProductSubCategory3").val();
			var product = checkNull(subCat1) + '-' + checkNull(subCat2) + '-' + checkNull(subCat3);
			var setVal = $('#ctcAvilableProducts').val();

			if (productInventory != '') {
				//remove required field styling
				$('#ctcProductInventory').removeAttr('style');
				if (setVal != '') {
					if (setVal.search(product) == -1) {
						var avilableProduct = setVal + ',\n' + product + '~' + productInventory;
					} else {
						var replaceConfirm = confirm(ctcTrans.confirmReplaceItem)
						if (!replaceConfirm) {
							return false;
						}
						var newVal = setVal.split(',');
						for (var i in newVal) {
							if (newVal[i].search(product) >= 0) {
								delete newVal[i];
								if (i != 0) {
									newVal[i] = '\n' + product + '~' + productInventory;
								} else {
									newVal[i] = product + '~' + productInventory;
								}
							}
						}
						var newProductSet = newVal.join(',');
						$('#ctcAvilableProducts').empty().val(newProductSet);
					}
				} else {
					var avilableProduct = product + '~' + $('#ctcProductInventory').val();
				}
				$("#ctcAvilableProducts").val(avilableProduct);
			} else {
				$('#ctcProductInventory').attr('placeholder', ctcTrans.requiredProductNum).css('border', '2px solid red');
				alert(ctcTrans.enterInventoryNum);
			}

		});



		//this section deals with activation ctcavilableProducts textarea also used with update product form
		$(document).on('click keyup', '#ctcAvilableProducts', function () {
			alert(ctcTrans.productVariationFormat);
		});



		// this section deals with add images to the product of primary product image

		$(document).on('click', '#ctcPrimaryImageLibrary', function () {
			var thumb = '';
			var id = '';
			// Accepts an optional object hash to override default values.
			var frame = new wp.media.view.MediaFrame.Select({
				// Modal title
				title: ctcTrans.selPrimaryImg,
				// Enable/disable multiple select
				multiple: true,
				// Library WordPress query arguments.
				library: {
					order: 'ASC',
					// [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo',
					// 'id', 'post__in', 'menuOrder' ]
					orderby: 'title',

					// mime type. e.g. 'image', 'image/jpeg'
					type: 'image',
					//filterable      : 'all',
					// Searches the attachment title.
					search: null,
					// Attached to a specific post (ID).
					uploadedTo: null,
					priority: 20,
					toolbar: 'main-insert',
				},
				button: {
					text: 'Select Image'
				}
			});

			// Fires when the modal closes.
			// @see media.view.Modal.close()
			frame.on('close', function () { });
			// Fires when a user has selected attachment(s) and clicked the select button.
			// @see media.view.MediaFrame.Post.mainInsertToolbar()
			frame.on('select', function () {
				var selectionCollection = frame.state().get('selection');
				var file = selectionCollection.map(function (attachment) {
					attachment = attachment.toJSON();
					thumb = attachment.sizes.full.url;
					id = attachment.id;
					return false;
				}).join();
				$('.ctcPrimaryPicThumb img').css('display', 'block').attr('src', thumb);
				$('#ctcPrimaryProductImage ').val(id);
			});

			// Open the modal.
			frame.open();
			return false;
		});


		//add additional images related to product

		$(document).on('click', '#ctcAdditionalImageLibrary', function () {
			var imagesId = [];
			var imagesThumb = [];
			// Accepts an optional object hash to override default values.
			var frame = new wp.media.view.MediaFrame.Select({
				// Modal title
				title: ctcTrans.selAdditionaImg,
				// Enable/disable multiple select
				multiple: 'add',
				// Library WordPress query arguments.
				library: {
					order: 'ASC',

					// [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo',
					// 'id', 'post__in', 'menuOrder' ]
					orderby: 'title',

					// mime type. e.g. 'image', 'image/jpeg'
					type: 'image',
					//filterable      : 'all',
					// Searches the attachment title.
					search: null,

					// Attached to a specific post (ID).
					uploadedTo: null,

					priority: 20,
					toolbar: 'main-insert',
				},
				button: {
					text: 'Select Images'
				},
				toolbar: {
					'main-insert': 'mainInsertToolbar'
				}
			});

			frame.on('select', function () {
				var i = 0;
				var selectionCollection = frame.state().get('selection');
				var file = selectionCollection.map(function (attachment) {
					attachment = attachment.toJSON();
					imagesId[i] = attachment.id;
					imagesThumb[i] = attachment.sizes.full.url;;
					i++;
				}).join();
				$('#ctcAddtionalProductImages').val(imagesId.join(','));
				var productCollage = '';
				var imgNum = imagesThumb.length;
				if (imgNum < 20) {
					if (imgNum > 3) {
						if (imgNum < 6) {
							var imgWidth = 180 / (imgNum);
							var imgHeight = 80 / (imgNum / 2);
						} else {

							var imgWidth = 320 / (imgNum);
							var imgHeight = 150 / (imgNum / 2);
						}
					} else {
						if (imgNum != 1) {
							var imgWidth = 60 / (imgNum / 2);
							var imgHeight = 50 / (imgNum / 2);
						} else {
							var imgWidth = 50;
							var imgHeight = 50;
						}
					}
				} else {
					var imgHeight = 35;
					var imgWidth = 35;
				}
				for (var i in imagesThumb) {
					productCollage += '<div class="ctcImgAlbum"><img   style="width:' + imgWidth + 'px;  height:' + imgHeight + 'px;" class="gridImg"  src="' + imagesThumb[i] + '" /></div>';
				}

				if (typeof masonry == 'function') {
					$('.ctcAdditionaImages').masonry('destroy');
				}
				$('.ctcAdditionaImages').empty().show().prepend(productCollage);
				//apply masonry function
				var container = $('.ctcAdditionaImages');
				container.imagesLoaded(function () {
					container.masonry({
						columnWidth: imgWidth,
					});
				});
			});
			// Open the modal.
			frame.open();
			return false;
		});
		//this section deals with adding product video
		$(document).on('click', '#ctcAddVideoLibrary', function () {
			// Accepts an optional object hash to override default values.
			var frame = new wp.media.view.MediaFrame.Select({
				// Modal title
				title: ctcTrans.selVideo,
				// Enable/disable multiple select
				multiple: false,
				// Library WordPress query arguments.
				library: {
					order: 'ASC',
					// [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo',
					// 'id', 'post__in', 'menuOrder' ]
					orderby: 'title',
					// mime type. e.g. 'image', 'image/jpeg'
					type: 'video',
					// Searches the attachment title.
					search: null,
					// Attached to a specific post (ID).
					uploadedTo: null
				}
			});
			frame.on('select', function () {
				var selectionCollection = frame.state().get('selection');
				var file = selectionCollection.map(function (attachment) {
					attachment = attachment.toJSON();
					videoUrl = attachment.url;
					videoId = attachment.id;
					videoMime = attachment.mime;
					$('#ctcVideoThumb').remove();
					var video = $('<video />', {
						id: 'ctcVideoThumb',
						src: videoUrl,
						type: videoMime,
						//controls: true
					});
					video.appendTo($('#ctcAddVideoLibrary').parent());
					$('#ctcProductVideo ').val(videoId);
					return false;
				}).join();
			});
			// Fires when the modal closes.
			// @see media.view.Modal.close()
			frame.on('close', function () { });
			// Open the modal.
			frame.open();
			return false;
		});

		//do stuffs with embded video like show control on click 
		$(document).on('click', '#ctcVideoThumb,#ctcVideoThumbOtherInfo,#ctcVideoThumbUpdate', function () {
			$(this).attr('controls', 'controls');
			this.play ? this.play() : this.pause();
		});

		$(document).on('mouseout', '#ctcVideoThumb, #ctcVideoThumbOtherInfo,#ctcVideoThumbUpdate', function () {
			$(this).removeAttr('controls', 'controls');
		});

		//this part handles ajax add porduct button click
		$(document).on('submit', '#ctcAddProductForm', function (event) {
			var emptyField = [];
			var i = 0;
			$('.ctcRequiredField').each(function () {

				var fieldVal = $(this).val();
				if (!$.trim(fieldVal)) {
					emptyField[i] = $(this).val();
					$(this).parent('div').find('span.ctcRequiredFieldWarning').remove();
					$(this).css('border', '2px solid red').parent('div').prepend('<span  style="margin-bottom:5px;color:red; font-weight:200;" class="dashicons dashicons-warning ctcRequiredFieldWarning">Required field</span>');
					i++;
				} else {
					$(this).removeAttr('style').parent('div').find('span.ctcRequiredFieldWarning').remove();
				}
			});
			if (emptyField.length >= 1) {
				return false;
			}

			var formData = $('#ctcAddProductForm').serializeArray();
			var data = {
				'action': 'ctcAddProduct',
				'productData': JSON.stringify(formData)
			}

			//trigger ajax to add product
			$.post(ajaxurl, data, function (response) {
				if (response >= 1) {
					alert(ctcTrans.productAdded);
					document.getElementById("ctcAddProductForm").reset();
					$('.ctcAdditionaImages').empty().hide();
					$('#ctcVideoThumb').remove();
					$('.ctcPrimaryPicThumb img').hide().attr('src', '');
				} else {
					alert(ctcTrans.couldNotAddproduct);
				}
			}).fail(function () {
				alert(ctcTrans.ajaxFail)
			});
			event.preventDefault();
			return false;
		});

		//function to load product's other info inside thickbox
		$(document).on('click', '#ctctPoductOtherInfo', function () {
			let id = $(this).attr('data-type-id');
			$.ctcOverlayEl({
				elemHeight: '570px',
				elemWidth: '470px',
				elemSelector: '#ctcOtherContent' + id
			});
		});
		//this section deal with product list tab update button
		$(document).on('click', '#ctcUpdateProduct', function () {
			let data = {
				'action': 'ctcGetProductUpdateForm',
				'id': $(this).attr('data-type-id')
			}

			$.ctcOverlayEl({
				elemHeight: '600px',
				elemWidth: '1100px',
				ajaxUrl: ajaxurl,
				ajaxData: data,
				ajaxMethod: 'post'
			});
		});

		/*script to remove single product variation from avilable product text area on add product and update product form */
		$(document).on('click', '#ctcRemoveAvilableProduct,#ctcRemoveAvilableProductUpdateForm', function () {
			if (!$('#ctcProductName').val()) {
				$('#ctcProductName').attr('placeholder', 'Product name cannot be empty').css({
					'border': '2px solid red'
				});

				return false;
			} else {
				$('#ctcProductName').removeAttr('style');

			}

			//get the value of selected option 
			var mainCat = $("#ctcProductCategorySelect").val();
			//if the main category is not selected
			if (!mainCat) {
				$("#ctcProductCategorySelect").css('border', '2px solid red');
				alert(ctcTrans.selectValidCategory);
				return false;
			} else {
				$("#ctcProductCategorySelect").removeAttr('style');

			}

			var subCat1 = $("#ctcProductSubCategory1").val();
			var subCat2 = $("#ctcProductSubCategory2").val();
			var subCat3 = $("#ctcProductSubCategory3").val();
			var product = checkNull(subCat1) + '-' + checkNull(subCat2) + '-' + checkNull(subCat3);
			$('#ctcProductInventory').removeAttr('style placeholder');
			var setVal = $('#ctcAvilableProducts').val();
			if (setVal.trim() == '') {
				alert(ctcTrans.noProductVariation);
			} else {
				if (setVal.search(product) >= 0) {
					var replaceConfirm = confirm(ctcTrans.optionRemoveVariation)
					if (!replaceConfirm) {
						return false;
					} else {
						var newVal = setVal.split(',');
						for (var i in newVal) {
							if (newVal[i].search(product) >= 0) {
								delete newVal[i];
							}
						}
						var newProductSet = newVal.filter(function (x) {
							return (x !== (undefined || null || ''));
						}).join(',');
						$('#ctcAvilableProducts').empty().val(newProductSet.trim());
					}
				} else {
					alert(ctcTrans.noProductCombination);
				}
			}
		});

		/****
		 * 
		 * 
		 * 
		 * This section deals with validation of product update form
		 * 
		 * 
		 * 
		 * 
		 * 
		 * 
		 */

		//validate the input field for add product form
		$(document).on('click', "#ctcAddAvilableProductUpdateForm", function () {
			if (!$('#ctcProductName').val()) {
				$('#ctcProductName').attr('placeholder', 'Product name cannot be empty').css({
					'border': '2px solid red'
				});
				return false;
			} else {
				$('#ctcProductName').removeAttr('style');
			}
			var productInventory = $('#ctcProductInventory').val();
			//get the value of selected option 
			var mainCat = $("#ctcProductCategorySelect").val();
			//if the main category is not selected
			if (!mainCat) {
				$("#ctcProductCategorySelect").css('border', '2px solid red');
				alert(ctcTrans.selectValidCategory);
				return false;
			} else {
				$("#ctcProductCategorySelect").removeAttr('style');

			}
			var subCat1 = $("#ctcProductSubCategory1").val();
			var subCat2 = $("#ctcProductSubCategory2").val();
			var subCat3 = $("#ctcProductSubCategory3").val();
			var product = checkNull(subCat1) + '-' + checkNull(subCat2) + '-' + checkNull(subCat3);
			//filter to check if number of product entered 
			if (productInventory == '') {
				$('#ctcProductInventory').attr('placeholder', ctcTrans.requiredProductNum).css({
					'border': '2px solid red'
				});

				return false
			}
			var setVal = $('#ctcAvilableProducts').val();
			if (setVal.trim() == '') {
				$('#ctcAvilableProducts').val(product + '~' + productInventory);
			} else {
				if (setVal.search(product) >= 0) {
					var replaceConfirm = confirm(ctcTrans.confirmReplaceItemDatabase)
					if (!replaceConfirm) {
						return false;
					}
					var newVal = setVal.split(',');
					for (var i in newVal) {
						if (newVal[i].search(product) >= 0) {
							delete newVal[i];
							if (i != 0) {
								newVal[i] = '\n' + product + '~' + productInventory;
							} else {
								newVal[i] = product + '~' + productInventory;
							}
						}
					}
					var newProductSet = newVal.join(',');
					$('#ctcAvilableProducts').empty().val(newProductSet);
				} else {
					var setOfProducts = setVal.trim().concat(',\n' + product + '~' + productInventory);
					$('#ctcAvilableProducts').empty().val(setOfProducts);
				}
			}
		});

		// this section deals with add images to the product of primary product image
		$(document).on('click', '#ctcPrimaryImageLibraryUpdate', function () {
			var thumb = '';
			var id = '';
			// Accepts an optional object hash to override default values.
			var frame = new wp.media.view.MediaFrame.Select({
				// Modal title
				title: ctcTrans.updatePrimaryImg,
				// Enable/disable multiple select
				multiple: true,
				// Library WordPress query arguments.
				library: {
					order: 'ASC',
					// [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo',
					// 'id', 'post__in', 'menuOrder' ]
					orderby: 'title',
					// mime type. e.g. 'image', 'image/jpeg'
					type: 'image',
					//filterable      : 'all',
					// Searches the attachment title.
					search: null,
					// Attached to a specific post (ID).
					uploadedTo: null,
					priority: 20,
					toolbar: 'main-insert',
				},
				button: {
					text: 'Select Image'
				}
			});
			// Fires when the modal closes.
			// @see media.view.Modal.close()
			frame.on('close', function () { });
			// Fires when a user has selected attachment(s) and clicked the select button.
			// @see media.view.MediaFrame.Post.mainInsertToolbar()
			frame.on('select', function () {
				var selectionCollection = frame.state().get('selection');
				var file = selectionCollection.map(function (attachment) {
					attachment = attachment.toJSON();
					thumb = attachment.sizes.full.url;
					id = attachment.id;
					return false;
				}).join();
				$('.ctcPrimaryPicThumbUpdate').removeClass('ctcActiveGalleryV').empty().append('<img src="' + thumb + '" style="display:block;"/>');
				$('#ctcPrimaryProductImageUpdate').val(id);
			});

			// Open the modal.
			frame.open();
			return false;
		});
		//update additional images related to product
		$(document).on('click', '#ctcAdditionalImageLibraryUpdate', function () {
			var imagesId = [];
			var imagesThumb = [];
			// Accepts an optional object hash to override default values.
			var frame = new wp.media.view.MediaFrame.Select({
				// Modal title
				title: ctcTrans.updateAdditionaImg,
				// Enable/disable multiple select
				multiple: 'add',
				// Library WordPress query arguments.
				library: {
					order: 'ASC',
					// [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo',
					// 'id', 'post__in', 'menuOrder' ]
					orderby: 'title',
					// mime type. e.g. 'image', 'image/jpeg'
					type: 'image',
					//filterable      : 'all',
					// Searches the attachment title.
					search: null,
					// Attached to a specific post (ID).
					uploadedTo: null,
					priority: 20,
					toolbar: 'main-insert',
				},
				button: {
					text: 'Select Images'
				},
				toolbar: {
					'main-insert': 'mainInsertToolbar'
				}
			});

			frame.on('select', function () {
				var i = 0;
				var selectionCollection = frame.state().get('selection');
				var file = selectionCollection.map(function (attachment) {
					attachment = attachment.toJSON();
					imagesId[i] = attachment.id;
					imagesThumb[i] = attachment.sizes.full.url;;
					i++;
				}).join();
				$('#ctcAddtionalProductImagesUpdate').val(imagesId.join(','));
				var productCollage = '';
				var imgNum = imagesThumb.length;
				if (imgNum < 20) {
					if (imgNum > 3) {
						if (imgNum < 6) {
							var imgWidth = 180 / (imgNum);
							var imgHeight = 80 / (imgNum / 2);
						} else {
							var imgWidth = 320 / (imgNum);
							var imgHeight = 150 / (imgNum / 2);
						}
					} else {
						if (imgNum != 1) {
							var imgWidth = 60 / (imgNum / 2);
							var imgHeight = 50 / (imgNum / 2);
						} else {
							var imgWidth = 50;
							var imgHeight = 50;
						}
					}
				} else {
					var imgHeight = 35;
					var imgWidth = 35;
				}

				for (var i in imagesThumb) {
					productCollage += '<div class="ctcImgAlbumUpdate" ><img   style="width:' + imgWidth + 'px;  height:' + imgHeight + 'px;" class="gridImg"  src="' + imagesThumb[i] + '" /></div>';
				}

				$('.ctcAdditionaImagesUpdate').masonry('destroy');
				$('.ctcAdditionaImagesUpdate').removeClass('ctcActiveGalleryV').empty().show().prepend(productCollage);
				var container = $('.ctcAdditionaImagesUpdate');
				container.imagesLoaded(function () {
					container.masonry({
						columnWidth: imgWidth,
					});

				});
			});
			// Open the modal.
			frame.open();
			return false;
		});
		//this section deals with updating product video
		$(document).on('click', '#ctcAddVideoLibraryUpdate', function () {
			// Accepts an optional object hash to override default values.
			var frame = new wp.media.view.MediaFrame.Select({
				// Modal title
				title: ctcTrans.updateVideo,
				// Enable/disable multiple select
				multiple: false,
				// Library WordPress query arguments.
				library: {
					order: 'ASC',
					// [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo',
					// 'id', 'post__in', 'menuOrder' ]
					orderby: 'title',
					// mime type. e.g. 'image', 'image/jpeg'
					type: 'video',
					// Searches the attachment title.
					search: null,
					// Attached to a specific post (ID).
					uploadedTo: null
				}
			});

			frame.on('select', function () {
				var selectionCollection = frame.state().get('selection');
				var file = selectionCollection.map(function (attachment) {
					attachment = attachment.toJSON();
					videoUrl = attachment.url;
					videoId = attachment.id;
					videoMime = attachment.mime;
					$('#ctcVideoThumbUpdate').remove();
					var video = $('<video />', {
						id: 'ctcVideoThumbUpdate',
						src: videoUrl,
						type: videoMime,
						//controls: true
					});
					video.appendTo($('#ctcAddVideoLibraryUpdate').parent());
					$('#ctcProductVideoUpdate').val(videoId);
					return false;
				}).join();
			});
			// Fires when the modal closes.
			// @see media.view.Modal.close()
			frame.on('close', function () { });
			// Open the modal.
			frame.open();
			return false;
		});

		/**
		 * 
		 * this is function to remove old entry and add new entry on 
		 * sucessfull product information update
		 * 
		 * 
		 */


		//function to update product table on sucessful update

		function updateProductOnSucess(formData, productId, returnedData, otherData) {
			//update table based on serialized array data 
			for (var i in formData) {
				switch (formData[i]['name']) {
					case 'avilableProducts':
						$('.' + formData[i]['name'] + productId).empty().prepend(formData[i]['value']);
						break;
					case 'preOrder':
					case 'featureProduct':
					case 'addtionalImages':
					case 'productVideo':
					case 'productDimensionWidth':
					case 'productDimensionLength':
					case 'productDimensionHeight':
					case 'productDimensionGirth':
					case 'ctcproductInventory':
					case 'subCategory3':
					case 'subCategory2':
					case 'subCategory1':
						break;
					default:

						$('#' + formData[i]['name'] + productId).empty().prepend(formData[i]['value']);
				}
			}
			//update blog post link in table
			if (!returnedData.postLink) {
				postTdContent = '<span class="dashicons dashicons-admin-post"></span>';
			} else {
				postTdContent = '<a href="' + returnedData.postLink + '" target="_blank"><span class="dashicons dashicons-admin-post"></span>';
			}
			//this section updates product list table based on data returned from server
			$('#productInventory' + productId).empty().append(returnedData.productInventory);
			$('#productDimension' + productId).empty().append(returnedData.productDimension);
			$('#subCategory' + productId).empty().append(returnedData.subCategory);
			$('#postLink' + productId).empty().append(postTdContent);
			$('#primaryPic' + productId + ' span').remove();
			$('#primaryPic' + productId + ' img').show().attr({
				'src': returnedData.primaryPicDir,
				'title': $('#primaryPic' + productId).attr('title')
			}).parent().removeAttr('data-ctc-active-gallery');
			var ctcPreOrder = (returnedData.preOrder == 1) ? 'Yes' : 'No';
			$('#preOrder' + productId).empty().append(ctcPreOrder);
			var ctcFeatureProduct = (returnedData.featureProduct == 1) ? 'Yes' : 'No';
			$('#featureProduct' + productId).empty().append(ctcFeatureProduct);
			//this sections updates other info section gallery and videos
			if (!$.isEmptyObject(otherData['gallery'])) {
				$('#addtionalImages' + productId).empty();
				for (var y in otherData['gallery']) {
					$('#addtionalImages' + productId).prepend('<img src="' + otherData['gallery'][y] + '"/>');

				}
			}
			$('#videoThumb' + productId).empty().append('<video id ="ctcVideoThumbOtherInfo"    src="' + otherData['videoSrc'] + '" type="' + otherData['videoType'] + '"></video>');
		}

		/**
		 * 
		 * 
		 * this section is to just to separate above function with rest of the 
		 * script
		 * 
		 * 
		 */

		//this part handles update product form submission
		$(document).on('submit', '#ctcUpdateProductForm', function (event) {
			var emptyField = [];
			var i = 0;
			$('.ctcRequiredField').each(function () {
				var fieldVal = $(this).val();
				if (!$.trim(fieldVal)) {
					emptyField[i] = $(this).val();
					$(this).parent('div').find('span.ctcRequiredFieldWarning').remove();
					$(this).css('border', '2px solid red').parent('div').prepend('<span  style="margin-bottom:5px;color:red; font-weight:200;" class="dashicons dashicons-warning ctcRequiredFieldWarning">Required field</span>');
					i++;
				} else {
					$(this).removeAttr('style').parent('div').find('span.ctcRequiredFieldWarning').remove();
				}

			});
			if (emptyField.length >= 1) {
				return false;
			}
			var formData = $('#ctcUpdateProductForm').serializeArray();
			var data = {
				'action': 'ctcUpdateProduct',
				'updatedData': JSON.stringify(formData)
			}
			//trigger ajax to add product
			$.post(ajaxurl, data, function (response) {
				var responseObj = JSON.parse(response);
				if (responseObj.updatedRow >= 1) {
					var otherData = [];
					var galleryImg = [];
					var x = 0;
					//get product gallery images to update product list on sucessfull update
					$('.ctcImgAlbumUpdate img').each(function () {
						galleryImg[x] = $(this).attr('src');
						x++;
					});
					otherData['gallery'] = galleryImg;
					//get product video to update product list on sucessfull update
					otherData['videoSrc'] = $('#ctcVideoThumbUpdate').attr('src');
					otherData['videoType'] = $('#ctcVideoThumbUpdate').attr('type');
					$('#ctcOverlayElClosebtn').trigger('click');
					var productId = $('#ctcProductIdUpdate').val();
					updateProductOnSucess(formData, productId, responseObj, otherData);
					alert(ctcTrans.productUpdated);
				} else {
					alert(ctcTrans.productNotUpdated);
				}
			}).fail(function () {
				alert(ctcTrans.ajaxFail)
			});
			event.preventDefault();
			return false;
		});

		//this section deals with purge product part
		$(document).on('click', '#ctcPurgeProductButton', function () {
			var purgeConfirm = confirm(ctcTrans.confirmPurge);
			if (purgeConfirm) {
				var data = {
					'action': 'ctcPurgeProduct',
					'productId': $('#ctcProductIdUpdate').val()
				}
				$.post(ajaxurl, data, function (response) {
					if (response >= 1) {
						alert(ctcTrans.productPurge);
						$('#ctcOverlayElClosebtn').trigger('click');
						$('#ctcProductRow' + data['productId']).remove();
					} else {
						alert(ctcTrans.couldNotPurge);
					}
				}).fail(function () {
					alert(ctcTrans.ajaxFail)
				});
			}
			return false;
		});

		//this section puts back the purged products into available product table  

		$(document).on('click', '#ctcAddPurgedProduct', function () {
			var data = {
				'action': 'ctcPutBackPurgedProduct',
				'productId': $(this).attr('data-type-id')

			}
			$.ctcOverlayEl({
				elemHeight: '600px',
				elemWidth: '1100px',
				ajaxUrl: ajaxurl,
				ajaxData: data,
				ajaxMethod: 'post'
			});
			event.preventDefault();
		});
		//this part handles ajax add porduct button click
		$(document).on('submit', '#ctcReAddProductForm', function (event) {
			var emptyField = [];
			var i = 0;
			$('.ctcRequiredField').each(function () {
				var fieldVal = $(this).val();
				if (!$.trim(fieldVal)) {
					emptyField[i] = $(this).val();
					$(this).parent('div').find('span.ctcRequiredFieldWarning').remove();
					$(this).css('border', '2px solid red').parent('div').prepend('<span  style="margin-bottom:5px;color:red; font-weight:200;" class="dashicons dashicons-warning ctcRequiredFieldWarning">Required field</span>');
					i++;
				} else {
					$(this).removeAttr('style').parent('div').find('span.ctcRequiredFieldWarning').remove();
				}
			});
			if (emptyField.length >= 1) {
				return false;
			}

			var formData = $('#ctcReAddProductForm').serializeArray();
			var data = {
				'action': 'ctcAddProduct',
				'productData': JSON.stringify(formData)
			}
			//trigger ajax to add product
			$.post(ajaxurl, data, function (response) {
				if (response >= 1) {
					var productId = $('#ctcProductIdUpdate').val();
					var newData = {
						'action': 'ctcRemovePurgedProduct',
						'productId': productId
					}
					$.post(ajaxurl, newData, function (response) {
						if (response === '1') {
							alert(ctcTrans.unPurged);
							$('#ctcPurgedProductRow' + productId).remove();
							$('#ctcOverlayElClosebtn').trigger('click');
						} else {
							alert(ctcTrans.couldNotUnPurged);
						}
					});
				} else {
					alert(ctcTrans.couldNotUnPurged);
				}
			}).fail(function () {
				alert(ctcTrans.ajaxFail)
			});
			event.preventDefault();
			return false;
		});


		/*
		 * 
		 * This section deals with javascript functionlaities of discount tab
		 * */


		$(document).on('click', '.ctcDiscountType', function () {
			if ($(this).attr('id') == 'ctcDiscountAmountCb') {
				var setVal = $('#ctcDiscountAmountCb').val();
				if ($('#ctcDiscountAmountCb').is(':checked')) {
					$('#ctcDiscountAmount').removeAttr('disabled');
					$('#ctcDiscountPercent').attr({
						'disabled': 'disabled',
						'value': ''
					});
					$('#ctcDiscountPercentCb').prop('checked', false);
				} else {
					$('#ctcDiscountPercent').removeAttr('disabled');
					$('#ctcDiscountAmount').attr({
						'disabled': 'disabled',
						'value': ''
					});
					$('#ctcDiscountPercentCb').prop('checked', true);
					$('#ctcDiscountAmountCb').prop('checked', false);
				}
			} else {
				if ($('#ctcDiscountPercentCb').is(':checked')) {
					$('#ctcDiscountPercent').removeAttr('disabled');
					$('#ctcDiscountAmount').attr({
						'disabled': 'disabled',
						'value': ''
					});
					$('#ctcDiscountAmountCb').prop('checked', false);
				} else {

					$('#ctcDiscountAmount').removeAttr('disabled');
					$('#ctcDiscountPercent').attr({
						'disabled': 'disabled',
						'value': ''
					});
					$('#ctcDiscountAmountCb').prop('checked', true);
					$('#ctcDiscountPercentCb').prop('checked', false);
				}
			}
		});

		//script to add dicount image
		$(document).on('click', '#ctcCouponImageLibrary', function () {
			var thumb = '';
			var id = '';
			// Accepts an optional object hash to override default values.
			var frame = new wp.media.view.MediaFrame.Select({
				// Modal title
				title: ctcTrans.addCouponImage,
				// Enable/disable multiple select
				multiple: true,
				// Library WordPress query arguments.
				library: {
					order: 'ASC',
					// [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo',
					// 'id', 'post__in', 'menuOrder' ]
					orderby: 'title',
					// mime type. e.g. 'image', 'image/jpeg'
					type: 'image',
					//filterable      : 'all',
					// Searches the attachment title.
					search: null,
					// Attached to a specific post (ID).
					uploadedTo: null,
					priority: 20,
					toolbar: 'main-insert',
				},
				button: {
					text: 'Select Image'
				}
			});
			// Fires when the modal closes.
			// @see media.view.Modal.close()
			frame.on('close', function () { });
			// Fires when a user has selected attachment(s) and clicked the select button.
			// @see media.view.MediaFrame.Post.mainInsertToolbar()
			frame.on('select', function () {
				var selectionCollection = frame.state().get('selection');
				var file = selectionCollection.map(function (attachment) {
					attachment = attachment.toJSON();
					thumb = attachment.sizes.full.url;
					id = attachment.id;
					return false;
				}).join();
				$('.ctcDiscountPicThumb').removeClass('ctcActiveGalleryV').empty().append('<img src="' + thumb + '" style="display:block;"/>');
				$('#ctcCouponImage').val(id);
			});
			// Open the modal.
			frame.open();
			return false;
		});

		//script to add discount to the database
		$(document).on('submit', '#ctcAddDiscountForm', function (event) {
			var data = {
				'action': 'ctcAddProductDiscount',
				'discountInfo': JSON.stringify($(this).serializeArray())
			}
			$.post(ajaxurl, data, function (response) {
				if (response == 1) {
					alert(ctcTrans.discountAdded);
					$(":input").each(function () {
						if ($(this).attr('id') != 'ctcAddDiscountButton') {
							$(this).val('');
							$('.ctcDiscountPicThumb').empty();
						}
					});
				} else {
					alert(ctcTrans.discountAddFail);
				}
			}).fail(function () {
				alert(ctcTrans.ajaxFail)
			});
			event.preventDefault();
			return false;
		});

		//script to get form with option to update or delete discount
		$(document).on('click', '#ctcUpdateDeleteDiscount', function () {
			let data = {
				'action': 'ctcGetDiscountUpdateForm',
				'discountId': $(this).attr('data-type-id')
			}
			$.ctcOverlayEl({
				elemHeight: '690px',
				elemWidth: '500px',
				ajaxUrl: ajaxurl,
				ajaxData: data,
				ajaxMethod: 'post'
			});
			return false;
		});
		//script to add dicount image
		$(document).on('click', '#ctcCouponImageLibraryUpdate', function () {
			var thumb = '';
			var id = '';
			// Accepts an optional object hash to override default values.
			var frame = new wp.media.view.MediaFrame.Select({
				// Modal title
				title: ctcTrans.updateCouponImage,
				// Enable/disable multiple select
				multiple: true,
				// Library WordPress query arguments.
				library: {
					order: 'ASC',
					// [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo',
					// 'id', 'post__in', 'menuOrder' ]
					orderby: 'title',
					// mime type. e.g. 'image', 'image/jpeg'
					type: 'image',
					//filterable      : 'all',
					// Searches the attachment title.
					search: null,
					// Attached to a specific post (ID).
					uploadedTo: null,
					priority: 20,
					toolbar: 'main-insert',
				},
				button: {
					text: 'Select Image'
				}
			});

			// Fires when the modal closes.
			// @see media.view.Modal.close()
			frame.on('close', function () { });
			// Fires when a user has selected attachment(s) and clicked the select button.
			// @see media.view.MediaFrame.Post.mainInsertToolbar()
			frame.on('select', function () {
				var selectionCollection = frame.state().get('selection');
				var file = selectionCollection.map(function (attachment) {
					attachment = attachment.toJSON();
					thumbImg = attachment.sizes.full.url;
					id = attachment.id;
					return false;
				}).join();
				$('.ctcDiscountPicThumbUpdate').removeClass('ctcActiveGalleryV').empty().append('<img src="' + thumbImg + '" style="display:block;"/>');
				$('input[name="couponImage"]').val(id);
			});
			// Open the modal.
			frame.open();
			return false;
		});
		/**
		 * 
		 * section to deal with update discount table and updatig discount table on sucess 
		 * 
		 */

		function ctcUpdateDiscountList(returnedData) {
			for (var key in returnedData) {
				switch (key) {
					case 'discountId':
					case 'update':
						break;
					case 'couponImage':
						$('a#ctcCouponImage' + returnedData['discountId'] + ' img').attr({
							'src': returnedData[key],
							'title': $('#ctcCouponImage' + returnedData['discountId']).attr("title")
						}).parent().removeAttr('data-ctc-active-gallery');
						break;
					case 'discountPercent':
					case 'discountAmount':
						if (returnedData[key] == 0) {
							$('#' + key + returnedData['discountId']).empty().prepend('----');
						} else {
							$('#' + key + returnedData['discountId']).empty().prepend(returnedData[key]);
						}
						break;
					default:
						$('#' + key + returnedData['discountId']).empty().prepend(returnedData[key]);
				}

			}
		}
		//script to handle update discount ajax
		$(document).on('submit', '#ctcUpdateDiscountForm', function (event) {
			var data = {
				'action': 'ctcUpdateProductDiscount',
				'updatedData': JSON.stringify($(this).serializeArray())
			}
			$.post(ajaxurl, data, function (response) {
				var responseObj = JSON.parse(response);
				if (responseObj.update == 1) {
					alert(ctcTrans.discountUpdated);
					$('#ctcOverlayElClosebtn').trigger('click');
					ctcUpdateDiscountList(responseObj);
				} else {
					alert(ctcTrans.discountUpdateFail);
				}
			}).fail(function () {
				alert(ctcTrans.ajaxFail)
			});
			event.preventDefault();
			return false;
		});
		//script to delete discount with ajax and update table
		$(document).on('click', '#ctcDeleteDiscountButton', function () {
			var data = {
				'action': 'ctcDeleteDiscount',
				'discountId': $('#ctcDiscountId').val()
			}
			$.post(ajaxurl, data, function (response) {
				if (response == 1) {
					alert(ctcTrans.discountDeleted);
					$('#ctcOverlayElClosebtn').trigger('click');
					$("#ctcDiscountListRow" + data['discountId']).remove();
				} else {
					alert(ctcTrans.discountDeleteFail);
				}
			}).fail(function () {
				alert(ctcTrans.ajaxFail)
			});
		});
		//script to handle business setting logo 
		$(document).on('click', '#ctcBusinessLogoMedia', function () {
			var thumb = '';
			var id = '';
			// Accepts an optional object hash to override default values.
			var frame = new wp.media.view.MediaFrame.Select({
				// Modal title
				title: ctcTrans.businessLogo,
				// Enable/disable multiple select
				multiple: true,
				// Library WordPress query arguments.
				library: {
					order: 'ASC',
					// [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo',
					// 'id', 'post__in', 'menuOrder' ]
					orderby: 'title',
					// mime type. e.g. 'image', 'image/jpeg'
					type: 'image',
					//filterable      : 'all',
					// Searches the attachment title.
					search: null,
					// Attached to a specific post (ID).
					uploadedTo: null,
					priority: 20,
					toolbar: 'main-insert',
				},
				button: {
					text: 'Select Image'
				}
			});

			// Fires when the modal closes.
			// @see media.view.Modal.close()
			frame.on('close', function () { });
			// Fires when a user has selected attachment(s) and clicked the select button.
			// @see media.view.MediaFrame.Post.mainInsertToolbar()
			frame.on('select', function () {
				var selectionCollection = frame.state().get('selection');
				var file = selectionCollection.map(function (attachment) {
					attachment = attachment.toJSON();
					thumb = attachment.sizes.thumbnail.url;
					return false;
				}).join();
				$('#ctcBusinessLogo img').show().attr('src', thumb);
				$('#ctcBusinessLogoDataImage').val(thumb);
			});
			// Open the modal.
			frame.open();
			return false;
		});
		//function to update pending order notification
		function updatePendingOrdersNotifications() {
			//ajax to update pending order notification
			var orderData = {
				'action': 'ctcUpdatePendingOrderNotification'
			}
			$.post(ajaxurl, orderData, function (pendingOrdersCount) {
				if (pendingOrdersCount === '0') {
					$('.ctcPendingOrderCount').remove();
				} else {
					$('.ctcPendingOrderCount').empty().append(pendingOrdersCount);
				}
			}).fail(function () {
				alert(ctcTrans.ajaxFail)
			});
		}
		//function to print shipping address
		function printShippingAddress(businessAddress, shippingAddress, customerName, order) {
			var mywindow = window.open('', 'PRINT', 'height=300,width=300,left=500');
			mywindow.document.write('<html><head><title>Shipping Address</title>');
			mywindow.document.write('</head><body  onload="window.print();">');
			mywindow.document.write('<div style="float:left;width:100%;display:block; margin-left:20px;"><h1 style="text-align:left;"> From</h1><address style="text-align:left;text-transform: uppercase;"><b>');
			mywindow.document.write(businessAddress);
			mywindow.document.write('</b></address></div>');
			mywindow.document.write('<div style="border-left:3px solid black; height:500px;margin-left:50%;margin-bottom:-220px;"></div>');
			mywindow.document.write('<div style="float:right; width:100%;display:block; margin-right:20px;"><h1 style="text-align:right;margin-right:50px;"> To </h1><address style="text-align:right;text-transform: uppercase;margin-bottom:20px;"><b>');
			mywindow.document.write(customerName + '<br>');
			mywindow.document.write(shippingAddress);
			mywindow.document.write('</b></address></div>');
			mywindow.document.write('<hr><div style="text-align:center;margin-top:20px;font-size:25px;">');
			mywindow.document.write(order);
			mywindow.document.write('</div></body></html>');
			mywindow.document.close(); // necessary for IE >= 10
			mywindow.focus(); // necessary for IE >= 10
			mywindow.close();
			return true;
		}

		//script to complete pending order
		$(document).on('click', '.ctcMarkShipped', function () {
			var checkedElement = $(this);
			var data = {
				'action': 'ctcCompleteOrder',
				'transactionId': $(this).val()
			}
			$.post(ajaxurl, data, function (response) {
				responseObj = JSON.parse(response);
				if (responseObj.complete == 'complete') {
					//update the pending order notification 
					updatePendingOrdersNotifications();
					if (confirm(ctcTrans.printShippingAddress)) {
						var businessAddress = $('#ctcBusinessAddressOrderTab').text().replace(/,/gi, '<br>');
						var shippingAddress = $('#shippingAddress' + data['transactionId']).text().replace(/,/gi, '<br>');
						var customerName = $('#ctcShippingCustomerName' + data['transactionId']).text();
						var order = $('#productPurchased' + data['transactionId']).html();
						printShippingAddress(businessAddress, shippingAddress, customerName, order);
					}
					$('#ctcPendingOrderRow' + data['transactionId']).remove();
					if ($('#ctcOrderList table tr').length <= 1) {
						$('#ctcOrderList table').remove();
						$('#ctcOrderList').append('<div class="dashicons-before dashicons-smiley"> You do not have any pending order left.</div>');
					}
					var inventoryMessage = '';
					if (typeof (responseObj.outOfStockProducts) != "undefined") {
						inventoryMessage += 'Out of Stock Products:\n';
						for (var i in responseObj.outOfStockProducts) {
							inventoryMessage += '-' + responseObj.outOfStockProducts[i] + '\n';
						}
					}

					if (typeof (responseObj.variation) != "undefined") {
						inventoryMessage += 'Out of Stock Product variation:\n';
						for (var a in responseObj.variation) {
							inventoryMessage += a + ':\n'
							inventoryMessage += ' ' + responseObj.variation[a] + '\n';
						}
					}
					if (inventoryMessage.length >= 1) {
						alert(inventoryMessage);
					}
				} else {
					checkedElement.prop('checked', false);
					var inventoryMessage = '';
					if (typeof (responseObj.outOfStockProducts) != "undefined") {
						inventoryMessage += ctcTrans.productOutOfStock + ':\n';
						for (var i in responseObj.outOfStockProducts) {
							inventoryMessage += '-' + responseObj.outOfStockProducts[i] + '\n';
						}

					}
					if (typeof (responseObj.variation) != "undefined") {
						inventoryMessage += ctcTrans.variationOutOfStock + ':\n';
						for (var a in responseObj.variation) {
							inventoryMessage += responseObj.variation[a] + '\n';
						}
					}

					if (inventoryMessage.length >= 1) {

						alert(ctcTrans.couldNotComplteOrder + "\n" + inventoryMessage + '\n' + ctcTrans.updateInventory);
					} else {
						alert(ctcTrans.couldNotComplteOrder);
					}
				}
			}).fail(function () {
				alert(ctcTrans.ajaxFail)
			});
		});

		//script to display form in modal box

		$(document).on('click', '.ctcDisplayRefundForm', function () {
			var data = {
				'action': 'ctcDisplayRefundForm',
				'transactionId': $(this).attr('data-type-tansactionid')
			}
			$.ctcOverlayEl({
				elemHeight: '220px',
				elemWidth: '380px',
				ajaxUrl: ajaxurl,
				ajaxData: data,
				ajaxMethod: 'post'
			});
		});
		//script to process refund 	   
		$(document).on('submit', '#ctcRefundForm', function (event) {
			var data = {
				'action': 'ctcProcessRefund',
				'refundData': $(this).serializeArray()
			}



			$.post(ajaxurl, data, function (response) {

				if (response === 'refundSuccessful') {
					alert(ctcTrans.refundSuccess);
					$('#ctcOverlayElClosebtn').trigger('click');
					var newRefund = parseFloat($('#ctcRefundtotal-' + data['refundData'][1]['value']).text()) + parseFloat(data['refundData'][0]['value']);
					$('#ctcRefundtotal-' + data['refundData'][1]['value']).empty().prepend(newRefund.toFixed(2));
				} else {
					alert(ctcTrans.refundFail);
				}
			});
			event.preventDefault();
			return false;
		});
		//script to style thickbox with purchase detail
		$(document).on('click', '.ctcPurchaseDetail', function () {
			$.ctcOverlayEl({
				elemHeight: '270px',
				elemWidth: '300px',
				elemSelector: '#contentPurchasedDetail' + $(this).attr('data-type-transactionid')
			});

		});
		//script to cancel order on customer request
		$(document).on('click', '.ctcCancelPendingOrder', function () {
			if (confirm("Are you sure you want to cancel this order?")) {
				var data = {
					'action': 'ctcCancelPendingOrder',
					'transactionId': $(this).attr('data-type-tansactionid')
				}
				$.post(ajaxurl, data, function (response) {
					if (response === '1') {
						//update the prnding orders notification
						updatePendingOrdersNotifications();
						$('#ctcPendingOrderRow' + data['transactionId']).remove();
						if ($('#ctcOrderList table tr').length <= 1) {
							$('#ctcOrderList table').remove();
							$('#ctcOrderList').append('<div class="dashicons-before dashicons-smiley"> You do not have any pending order left.</div>');
						}
					} else {
						alert(ctcTrans.couldNotCanelOrder);
					}
				}).fail(function () {
					alert(ctcTrans.ajaxFail)
				});
			}

		});


		//script to display info on admin panel info section
		$('.ctcAdminInstruction h4,.ctcAdminPanelShortcodes h4,.ctcAdminPanelRestApi h4,.ctcAdminPanelImageSpec h4,.ctcAdminPanelProductChart h4,.ctcAdminSalesActivity h4,.ctcAdminNotice h4').click(function () {
			var h4El = $(this);
			if (h4El.hasClass('ctcShowProductChart') && $('#ctcProductPreviewChart ul').length === 0) {
				$.post(ajaxurl, {
					'action': 'ctcProductBarForChart'
				}, function (response) {
					$('#ctcProductPreviewChart').append('<ul></ul>');
					h4El.siblings().slideToggle(100, function () {
						$(response).filter('div').each(function (i) {
							var productBarHtml = $(this).html();
							setTimeout(function () {
								$('#ctcProductPreviewChart ul').append(productBarHtml)
							}, (500 * i));
						});
					});

				}).fail(function () {
					alert(ctcTrans.ajaxFail)
				});
			} else if (h4El.hasClass('ctcShowSalesActivity') && $('.ctcSalesReportList ul').length === 0) {
				$.post(ajaxurl, {
					'action': 'ctcAjaxSalesReport'
				}, function (response) {
					$('.ctcSalesReportList ').append('<ul></ul>');
					h4El.siblings().slideToggle(100, function () {
						$(response).filter('li').each(function (i) {
							var salesActHtml = $(this).html();

							$('.ctcSalesReportList ul').append('<li>' + salesActHtml + '</li>')

						});
					});
				}).fail(function () {
					alert(ctcTrans.salesReportNotLoaded);
				});
			} else {
				h4El.siblings().fadeToggle(1000, function () { });
			}
			h4El.toggleClass('showingContent');
		});
		if ($('.ctcAdminNotice').length === 1) {
			$('.ctcAdminNotice h4').trigger('click');
		}
		/**
		 * 
		 * 
		 * never write code beyound this point just for note about what need to be done next
		 * 
		 */
	});
}(jQuery));

window.addEventListener('load', () => {
	let jsMas = new jsMasonry('.ctcPostImgGallery .gallery ');
})