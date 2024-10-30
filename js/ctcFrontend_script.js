(function ($) {
    $(document).ready(function ($) {

        $('.ctcCategoryLinkContainer').tooltip(
            {
                position: {
                    my: "center ",
                    at: "center ",
                }
            });

        //function to javascript number format
        function addCommas(nStr) {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }

        //script to reinstate cart on page load
        if (localStorage.getItem("ctcWidgetCartData")) {
            ctcReinstateCart();
        }

        //check for empty cart
        ctcCheckWidgetEmptyCart();



        // apply required masonry
        const prodMas = new jsMasonry('.ctcFeaturedProductList,.ctcProductCategoriesMain,.ctcCategoryPageProductList,.ctcMetaPageProductList,.ctcDiscountProductList, .ctcPostImgGallery .gallery');
        $('.ctcSingleProductGallery span:nth-child(2),.ctcSingleProductGallery span:first-child ').on('click', function (event) {

            var scrollContainer = $(".ctcSingleProductGallery");

            if ($(this).is('.ctcSingleProductGallery span:first-child')) {

                scrollContainer.animate({
                    scrollLeft: scrollContainer.scrollLeft() - 80
                }, 500);

            } else {

                scrollContainer.animate({
                    scrollLeft: scrollContainer.scrollLeft() + 80
                }, 500);
            }

        });


        /**
         * 
         * 
         *  script to handle on logout destroy web session
         *  
         */


        $(document).on('click', '#ctCommerceUserLogout', function () {

            localStorage.removeItem("ctcWidgetCartData");

        });


        /**
         * 
         * 
         * 
         * this section contains basic password validation for user registration and update
         * 
         */

        function ctcCheckPasswordMatch(password, confirmPassword, confirmPasswordId) {

            if (password !== confirmPassword) {
                $('.ctcConfirmPasswordError').show();

                $("#" + confirmPasswordId).css('border', '2px solid red');
                return false;
            } else {

                $('.ctcConfirmPasswordError').hide();
                $("#" + confirmPasswordId).removeAttr('style');

                return true;
            }

        }


        /**
         * 
         * end of wp media modal box
         * 
         * 
         */

        //function to show user rgistration form with ajax
        $(document).on('click', '#ctCommerceUserRegistration', function () {

            var data = {
                'action': 'ctcGetUserRegistrationForm'
            }

            $.post(ctc_ajax_url, data, function (response) {

                $.ctcOverlayEl({
                    elemHeight: '550px',
                    elemWidth: '1000px',
                    ajaxUrl: ctc_ajax_url,
                    ajaxData: data,
                    ajaxMethod: 'post'
                });

            });

            return false;
        });



        //script to register user with ajax
        $(document).on('submit', '#ctcUserRegistrationForm', function (event) {

            var password = $("input[name='customerPassword']").val();
            var confirmPassword = $("input[name='customerConfirmPassword']").val();
            var confirmPasswordId = $("input[name='customerConfirmPassword']").attr('id');

            if (ctcCheckPasswordMatch(password, confirmPassword, confirmPasswordId)) {

                var data = {

                    'action': 'ctcRegisterUser',
                    'userInfo': JSON.stringify($(this).serializeArray())
                }

                $.post(ctc_ajax_url, data, function (response) {

                    if (response == 1) {

                        $.ctcOverlayEl({
                            modalMessage: 'Account sucesfully created.'
                        });
                        $('button.media-modal-close, #ctcUserRegistrationFormReset').trigger('click');

                    } else {
                        var errorUser = 'Error :';
                        responseObj = JSON.parse(response);
                        for (var i in responseObj['errors']) {
                            errorUser = errorUser.concat('<br/>' + responseObj['errors'][i]);
                        }

                        $.ctcOverlayEl({
                            modalMessage: errorUser
                        });

                    }

                });

            }
            event.preventDefault();
            return false;
        });




        //function to get user update form
        $(document).on('click', '#ctCommerceUserInfoUpdate', function () {

            var modalCss = {
                'padding': '50px',
                ' outline': 'none!important'
            };



            var data = {

                'action': 'ctcGetUserInfoUpdateForm'
            }

            $.ctcOverlayEl({
                elemHeight: '550px',
                elemWidth: '1000px',
                ajaxUrl: ctc_ajax_url,
                ajaxData: data,
                ajaxMethod: 'post'
            });

            return false;

        });


        //script to update user information

        $(document).on('submit', '#ctcUserUpdateForm', function (event) {

            var password = $("input[name='customerPassword']").val();
            var confirmPassword = $("input[name='customerConfirmPassword']").val();
            var confirmPasswordId = $("input[name='customerConfirmPassword']").attr('id');

            if (ctcCheckPasswordMatch(password, confirmPassword, confirmPasswordId)) {
                var data = {
                    'action': 'ctcUpdateUserInfo',
                    'updatedInfo': JSON.stringify($(this).serializeArray())
                }
                $.post(ctc_ajax_url, data, function (response) {
                    if (response >= 1) {
                        $.ctcOverlayEl({ modalMessage: 'Information sucessfully updated.' });

                        $('button.media-modal-close, #ctcUserUpdatenFormReset').trigger('click');
                    } else {
                        $.ctcOverlayEl({
                            modalMessage: 'Information could not be updated at this time.<br/>Try loging out log back in and update'
                        });
                    }

                });

            }
            event.preventDefault();
            return false
        });

        /*script to add intems to the end of page on scroll*/


        $(window).on("scroll", function () {
            var scrollHeight = $(document).height();
            var scrollPosition = $(window).height() + $(window).scrollTop();

            //just kill process if all featured product are loaded
            if ($('#ctcSortProductSelect').attr('data-type-allproduct') === 'yes') {

                return false;
            } else {

                if (scrollHeight === scrollPosition) {

                    var data = {

                        'action': 'ctcAddNewFeaturedProducts',
                        'offset': $('div.ctcStorePageMain').attr('data-type-rowoffset')
                    }


                    if (data['offset']) {
                        $.post(ctc_ajax_url, data, function (response) {

                            if (response.length > 22) {

                                $('.ctcFeaturedProductList').append(response);

                                prodMas.layBrks(document.querySelector('.ctcFeaturedProductList'));
                                $('[id]').each(function (i) {
                                    $('[id="' + this.id + '"]').slice(1).remove();
                                });
                                var productCount = $('.ctcFeaturedProductContent').length;

                                $('div.ctcStorePageMain').attr('data-type-rowoffset', parseInt(productCount));

                            } else {

                                $('div.ctcStorePageMain').removeAttr('data-type-rowoffset');
                            }

                        });
                    }
                }

            }
        });



        /**
         * 
         * Script for Widget Product cart functionalities
         * 
         * 
         */

        //function to check if cartdoes not have item if no do not display
        function ctcCheckWidgetEmptyCart() {
            if (!cartChecked) {
                if ($('#ctcCartWidgetTable tr').length === 0) {
                    $('.ctcHideOnEmptyCart').animate({
                        opacity: 0,
                        left: "+=100",
                    }, 150, function () {


                        $('#ctcWidgetEmptyCartMessage,#ctcPageEmptyCartMessage').empty().prepend("<h5> Empty Cart.</h5>").show("medium");

                        $('#ctcWidgetEmptyCartMessage h5,#ctcPageEmptyCartMessage h5').each(function () {

                            if ($(this).html().length === 0) {

                                $(this).remove();
                            }

                        });

                    }).hide("medium");


                } else {
                    $('#ctcWidgetEmptyCartMessage,#ctcPageEmptyCartMessage').empty();
                    $('.ctcHideOnEmptyCart').animate({
                        opacity: 1,
                        left: "+=100",
                    }, 150, function () {

                    }).show("fast");

                }
            }

            var cartChecked = true;

        }

        //remove product on delete click
        $(document).on('click', '.ctcRemoveWidgetCartItem', function () {
            var productId = $(this).attr('data-type-id');

            if (confirm("Do you want to remove this item from cart?")) {

                $("#ctcCartWidgetTr" + productId).remove();
                $("#ctcPageCartItem" + productId).remove();

                //calculate and prepend grand total
                ctcCartCalculateTotal();

            }

            return false;

        });

        function ctcAddNewWidgetCartItemVariation(id, newProductVariation) {

            var setProductVariation = $('#ctcWidgetCartProductVariation-' + id).val();
            if (setProductVariation.search(newProductVariation) !== -1) {

                var setVarArray = setProductVariation.split(',');
                for (var i in setVarArray) {

                    if (setVarArray[i].search(newProductVariation) !== -1) {

                        var matchingVariation = setVarArray[i].split(':');

                        delete setVarArray[i];

                        setVarArray[i] = newProductVariation + ":" + (parseInt(matchingVariation[1]) + 1);

                        var newVariationSet = setVarArray.join(',');
                        $('#ctcWidgetCartProductVariation-' + id).val(newVariationSet);
                        $('#ctcWidgetCartProductVariationTooltip-' + id).removeAttr('title').attr('title', $('#ctcWidgetCartProductVariation-' + id).val());
                    }

                }

            } else {

                $('#ctcWidgetCartProductVariation-' + id).val(setProductVariation + ',' + newProductVariation + ':1');
            }

        }


        //function to calculate grand total and set cookie too
        function ctcCartCalculateTotal() {
            var newGrandTotal = 0;
            var discountAmount = 0;
            var shippingCost = 0;
            var tax = parseFloat($('#ctcPageCartItemGrid').attr('data-type-tax'));

            $('input.ctcIndividualProductTotal').each(function () {

                newGrandTotal = newGrandTotal + parseFloat($(this).val());

            });

            discountAmount = parseFloat($('#ctcPromoCodeSaving').val());

            shippingCost = parseFloat($('#ctcTotalShippingCost').val());

            if (tax >= 0) {

                var taxAmount = (newGrandTotal / 100) * tax;

                var amountAfterTaxDiscountShipping = newGrandTotal + taxAmount - discountAmount + shippingCost;
                $('#ctcPageCartGrandTotalInput').val(amountAfterTaxDiscountShipping.toFixed(2));
                $('#ctcCartGrandTotal').val(newGrandTotal.toFixed(2));
                $('#ctcWidgetGrandTotalAmount').empty().prepend(addCommas(newGrandTotal.toFixed(2)));
                $('#ctcPageCartGtotal').empty().prepend(addCommas(amountAfterTaxDiscountShipping.toFixed(2)));
                $('#ctcPageCartTaxAmount').empty().html(addCommas(taxAmount.toFixed(2)));

            } else {
                $('#ctcCartGrandTotal, #ctcPageCartGrandTotal input').val(newGrandTotal.toFixed(2));
                $('#ctcWidgetGrandTotalAmount,#ctcPageCartGtotal').empty().prepend(addCommas(newGrandTotal.toFixed(2)));
            }
            ctcCheckWidgetEmptyCart();

            //function to also save cart in session storage
            cartData = $("#ctcProductCartWidget").html();
            setCtcCartLocalStorage(cartData);
        }


        //function to multiply price if product exists
        function ctcCartWidgetDuplicateProduct(id, price, productVariation) {
            var addNewProduct = true;
            var productInCart = $('#ctcProductCartWidgetForm').serializeArray();

            for (var i in productInCart) {

                switch (productInCart[i]['name']) {
                    case 'productId':
                        if (productInCart[i]['value'] === id) {

                            var newProductCount = parseFloat($("#ctcWidgetCartProductCount-" + id).val()) + 1;
                            var newProductTotal = newProductCount * price;

                            //function to add new product variation 
                            ctcAddNewWidgetCartItemVariation(id, productVariation);

                            $("#ctcWidgetCartProductCount-" + id).val(newProductCount);
                            $("#ctcWidgetCartProductCountDisplay-" + id).empty().text(newProductCount);
                            $("#ctcWidgetCartProductTotal-" + id).empty().append(addCommas(newProductTotal.toFixed(2)));
                            $('#ctcIndividualProductTotal-' + id).val(newProductTotal);
                            var addNewProduct = false;

                        }
                        break;
                }
            }
            return addNewProduct;
        }




        //function to prepare product to add in cart
        function prepareProductForCart(price, id, name, thumb, productVariation) {


            //check if product variation is avilable
            if (!productVariation || productVariation.length <= 0) {
                delete productVariation;
                productVariation = name;
            }

            //function to update cart on duplicate product	

            if (ctcCartWidgetDuplicateProduct(id, price, productVariation)) {

                var cartReady = '';
                cartReady += "<tr id='ctcCartWidgetTr" + id + "' class='ctcCartWidgetItem'>";
                cartReady += "<input class='ctcWidgetProductId' type='hidden' name='productId' value='" + id + "' />";
                cartReady += "<input  type='hidden' name='productImage' value='" + thumb + "' />";
                cartReady += "<td class='ctcWidgetCartImageTd' ><img class='ctcWidgetCartProductImage ctcCartImg' src='" + thumb + "' title='" + name + "' /></td>";
                cartReady += "<input type='hidden' id='ctcWidgetCartProductName-" + id + "' name='productName' value='" + name + "' />";
                cartReady += "<input type='hidden' id='ctcCartWidgetPrice" + id + "' name='productPrice' value='" + price + "' />";
                cartReady += "<td id='ctcWidgetCartProductCountDisplay-" + id + "'> 1 </td>";
                cartReady += "<input id='ctcWidgetCartProductCount-" + id + "' type='hidden' class='ctcCartWidgetCount' data-type-price='" + price + "' data-type-id='" + id + "'   name='productCount'  value='1' />";
                cartReady += "<input id='ctcWidgetCartProductVariation-" + id + "' class='ctcWidgetProductVariation' type='hidden' name='productVariation' value='" + productVariation + ":1' />";
                cartReady += "<td id='ctcWidgetCartProduct" + id + "'><a  href='JavaScript:void(0);' title='' class='ctcWidgetVaritionShowtoolTip' data-type-id='" + id + "' >Variation</a></td>";
                cartReady += "<td> <span id='ctcWidgetCartProductTotal-" + id + "' >" + addCommas(price) + "</span></td>";
                cartReady += "<input class='ctcIndividualProductTotal' type='hidden' id='ctcIndividualProductTotal-" + id + "' name='ctcIndividuaProductTotal' value='" + price + "' />";
                cartReady += "<td><a  class='ctcRemoveWidgetCartItem dashicons-before dashicons-trash' id='ctcRemoveWidgetCartItem" + id + "' data-type-id='" + id + "' href='JavaScriptVoid:(0)'></a> </td>";
                cartReady += "</tr>";
            }

            return cartReady;
        }



        //javascript to add product to the cart
        $(document).on('click', '.ctcAddToCartLink', function () {

            var id = $(this).attr('data-type-id');
            let subCat1 = null != $('#ctcProductSubCat1Select-' + id).val() ? $('#ctcProductSubCat1Select-' + id).val().trim() : '';
            let subCat2 = null != $('#ctcProductSubCat2Select-' + id).val() ? $('#ctcProductSubCat2Select-' + id).val().trim() : '';
            let subCat3 = null != $('#ctcProductSubCat3Select-' + id).val() ? $('#ctcProductSubCat3Select-' + id).val().trim() : '';
            let i = 0;
            let subCat123 = subCat1 + '-' + subCat2 + '-' + subCat3;

            $('#ctcProductSelect-' + id + ' option').each(function () {
                if ($(this).val().trim() === subCat123) {
                    $(this).prop('selected', true);
                    $(this).attr('data-variation', subCat123);
                    $(this).closest('select').trigger('change');
                    $('.ctcProductSelectClass-' + id).attr('data-type-variation', productVariation);
                    i++;
                } else {

                    if (i <= 0) {
                        $('#ctcProductSelect-' + id + ' option:first-child').prop('selected', true)
                    }


                }

            })


            var price = $(this).attr('data-type-price');
            var name = $(this).attr('data-type-name');
            var thumb = $(this).attr('data-type-thumb');
            var productVariation = $(this).attr('data-type-variation');


            //check if empty option is selcted 
            if ($('#ctcProductSelect-' + id).val() == 'emptyOption') {

                $.ctcOverlayEl({
                    modalMessage: 'Select available product combination first.'
                });

                return false;

            }

            $('#ctcCartWidgetTable').append(prepareProductForCart(price, id, name, thumb, productVariation));
            //add grand total on new product add
            ctcCartCalculateTotal();
            //check for empty cart on 
            ctcCheckWidgetEmptyCart();
            return false;
        });



        /**
         * 
         * function to create tooltip cart preview
         * 
         */

        function ctcToolTipCart() {

            var cartProductData = $('#ctcProductCartWidgetForm').serializeArray();
            var allItemsHtml = '<tr id="toolTipHeader" ><th></th><th>Product</th><th>Qty</th><th>Total</th></tr> ';
            var grandTotal = 0;

            //one as grand total on widget is not removed
            if (cartProductData.length > 1) {

                for (var i in cartProductData) {

                    var itemDivContent = '';
                    var itemDescription = [];

                    switch (cartProductData[i]['name']) {
                        case 'productImage':
                            itemDivContent += "<tr class='ctcToolTipCartItem' ><td class='ctcToolTipImg'><img src='" + cartProductData[i]['value'] + "' title=''/></td>";
                            break;
                        case 'productName':
                            itemDivContent += "<td class='ctcToolTipCartName'>" + cartProductData[i]['value'] + "</td>";
                            break;
                        case 'productCount':
                            itemDivContent += "<td class='ctcToolTipCartItemCount'>" + cartProductData[i]['value'] + "</td> ";
                            break;
                        case 'ctcIndividuaProductTotal':
                            itemDivContent += "<td class='ctcToolTipCartItemTotal'>" + addCommas(parseFloat(cartProductData[i]['value']).toFixed(2)) + "</td></tr>";
                            grandTotal = grandTotal + parseFloat(cartProductData[i]['value']);
                            break;
                    }


                    //html for all product cart data grid
                    allItemsHtml += itemDivContent;
                    delete (itemDivContent[i]);
                }
                allItemsHtml += '<tr id="ctcToolTipCartTotal"><td colspan="2"> Sub Total :</td><td colspan="2">' + addCommas(grandTotal.toFixed(2)) + '</td></td>';
            }
            return allItemsHtml;
        }

        //script to add $ ui tooltip
        $(document).on('mouseenter', '.ctcCartToolTip', function () {

            $(this).attr('title', 'Cart Preview');
        });
        //tooltip for cart

        $(function () {
            $(document).tooltip({
                content: function () {
                    var element = $(this);
                    if (element.hasClass('ctcWidgetVaritionShowtoolTip')) {
                        return $("#ctcWidgetCartProductVariation-" + element.attr('data-type-id')).val();
                    } else if (element.hasClass('ctcPageVaritionShowtoolTip')) {
                        return $("#ctcPageCartProductVariation-" + element.attr('data-type-id')).val();
                    } else if (element.hasClass('ctcCartToolTip')) {

                        return function () {

                            if ($('#ctcCartWidgetTable tr').length !== 0) {
                                return '<div class="ctcToolTipNavCart"><span><b>Cart Preview </b></span> <table>' + ctcToolTipCart() + '</table></div>';
                            } else {
                                return "<h5 class='ctcEmptyCartTooltip'> Empty!</h5>";
                            }
                        }

                    } else if (element.is('img')) {
                        if (element.hasClass('ctcCartImg')) {
                            return '<div class="ctcToolTipImg"><h5>' + element.attr('title') + '</h5><img src="' + element.attr('src') + '"/></div>';
                        }
                    }
                },

                show: {
                    effect: "slideDown",
                    delay: 300
                },
                position: {
                    within: $(this),
                    my: "center bottom-5",
                    at: "center top",
                    using: function (position, feedback) {
                        $(this).css(position);
                        $("<div>")
                            .addClass("arrow")
                            .addClass(feedback.vertical)
                            .addClass(feedback.horizontal)
                            .appendTo(this);
                    }
                }

            });
        });

        //function to add select product variation to the cart data type variation
        $(document).on('change', 'select.ctcProductVariationSelect', function () {

            var productVariation = $("option:selected", this).val();
            var productId = $(this).attr('data-type-id');
            var preOrder = $("option:selected", this).attr('data-type-preorder');
            if (preOrder == 'yes') {
                $('#ctcPreOrderAvilable-' + productId).css('visibility', 'visible');
            } else {
                $('#ctcPreOrderAvilable-' + productId).css('visibility', 'hidden');;
            }

            $('.ctcProductSelectClass-' + productId).attr('data-type-variation', productVariation);

        });

        /**
         * 
         * script to select subcategories
         * 
         */
        $(document).on('change', '.ctcProductSelCat1', function () {
            let subCat1 = $(this).val().trim();
            let productId = $(this).closest('select').attr('data-type-id');
            $('#ctcProductSubCat1Select-' + productId + ' option:first-child').prop('disabled', true);
            $('#ctcProductSelect-' + productId + ' option').each(function () {

                let prodVar = $(this).val().trim();
                if ('emptyOption' != prodVar) {
                    $('#ctcProductSubCat2Select-' + productId + ' option').each(function () {

                        if (prodVar.search(subCat1 + '-' + $(this).val()) === 0) {
                            $(this).attr('data-sc-one', subCat1);
                            $(this).prop('disabled', false);

                        } else {
                            if ($(this).attr('data-sc-one') != subCat1) {
                                $(this).prop('disabled', true);
                            }
                        }
                    })
                }
            });
        })

        $(document).on('change', '.ctcProductSelCat2', function () {
            let productId = $(this).closest('select').attr('data-type-id');
            let scOneTwo = $('#ctcProductSubCat1Select-' + productId).val().trim() + '-' + $(this).val().trim();
            $('#ctcProductSelect-' + productId + ' option').each(function () {
                let prodVar = $(this).val().trim();
                $('#ctcProductSubCat3Select-' + productId + ' option').each(function () {
                    if (prodVar.search(scOneTwo + '-' + $(this).val()) === 0) {
                        $(this).attr('data-sc-one-two', scOneTwo);
                        $(this).prop('disabled', false);
                    } else {
                        if ($(this).attr('data-sc-one-two') != scOneTwo) {
                            $(this).prop('disabled', true);
                        }
                    }
                });
            });

        })

        /**
         * 
         * script to create cart for the page
         * 
         */

        function ctcProductCartPage() {

            var cartProductData = $('#ctcProductCartWidgetForm').serializeArray();
            var grandTotal = 0;
            var allItemsHtml = ' ';
            var taxRate = parseFloat($('#ctcPageCartItemGrid').attr('data-type-tax'));
            var currency = $('#ctcPageCartItemGrid').attr('data-type-currency');


            //one as grand total on widget is not removed
            if (cartProductData.length > 1) {

                for (var i in cartProductData) {

                    var itemDivContent = '';
                    var itemDescription = [];

                    switch (cartProductData[i]['name']) {
                        case 'productId':
                            var productId = cartProductData[i]['value'];
                            itemDivContent += "<div id='ctcPageCartItem" + productId + "' class='ctcPageCartItem' >\n";
                            itemDivContent += "<input type='hidden' class='ctcPageCartProductId' name='productId[]' value='" + productId + "'  />\n";

                            break;
                        case 'productImage':
                            itemDivContent += "<span class='ctcPageCartImg'><img class='ctcCartImg' src='" + cartProductData[i]['value'] + "' title=''/></span>\n";
                            itemDivContent += "<input type='hidden' name='productImage-" + productId + "' value='" + cartProductData[i]['value'] + "' />\n";

                            break;
                        case 'productName':
                            itemDivContent += "<span class='ctcPageCartName'>" + cartProductData[i]['value'] + "</span>\n";
                            itemDivContent += "<input type='hidden' name='productName-" + productId + "' value='" + cartProductData[i]['value'] + "' />\n";


                            break;
                        case 'productPrice':
                            itemDivContent += "<span>" + addCommas(cartProductData[i]['value']) + "</span>\n";
                            itemDivContent += "<input type='hidden' name='productPrice-" + productId + "' value='" + cartProductData[i]['value'] + "' />\n";

                            break;
                        case 'productCount':
                            itemDivContent += "<span>" + cartProductData[i]['value'] + "</span>\n";
                            itemDivContent += "<input id='ctcProductCount-" + productId + "' type='hidden' class='ctcPageCartProductCount' name='productCount-" + productId + "' value='" + cartProductData[i]['value'] + "' />\n";

                            break;
                        case 'productVariation':
                            itemDivContent += "<span style='display:none;'>" + cartProductData[i]['value'] + "</span>\n";
                            itemDivContent += "<input type='hidden' id='ctcPageCartProductVariation-" + productId + "' name='productVariation-" + productId + "' value='" + cartProductData[i]['value'] + "' />\n";

                            break;
                        case 'ctcIndividuaProductTotal':
                            itemDivContent += "<span>" + addCommas(parseFloat(cartProductData[i]['value']).toFixed(2)) + "</span>\n";
                            itemDivContent += "<input type='hidden' name='productTotal-" + productId + "'  value='" + cartProductData[i]['value'] + "'/>\n";
                            itemDivContent += "<a  href='JavaScript:void(0);' title='' class='ctcPageVaritionShowtoolTip' data-type-id='" + productId + "' >Variation</a></td>";
                            itemDivContent += '<a class="ctcPageCartItemRemove" data-type-id="' + productId + '" href="JavaScript:void(0);"><span class="dashicons dashicons-trash "></span></a></div>';

                            grandTotal = grandTotal + parseFloat(cartProductData[i]['value']);

                            break;

                    }

                    //html for all product cart data grid
                    allItemsHtml += itemDivContent;

                    delete (itemDivContent[i]);
                }

                var taxAmount = grandTotal * (taxRate / 100);
                var grandTotalAfterTax = grandTotal + taxAmount;

                //data for stripe checkout
                ctcCheckOutAmount = grandTotalAfterTax;
                ctcDataDescription = 'Total Amount : ' + grandTotalAfterTax;
                $(allItemsHtml).hide().appendTo('#ctcPageCartItemGrid').show("normal");
                $('#ctcPageCartTaxAmount').html(addCommas(taxAmount.toFixed(2)));
                $('#ctcPageCartGrandTotalInput').val(grandTotalAfterTax);
                $('#ctcPageCartGtotal').html(addCommas(grandTotalAfterTax.toFixed(2)));
                ctcCheckWidgetEmptyCart();
            }

        }


        //script to remove item from product cart page
        $(document).on('click', '.ctcPageCartItemRemove', function () {

            var productId = $(this).attr('data-type-id');
            if (confirm("Do you want to remove this item from cart?")) {
                $("#ctcCartWidgetTr" + productId).remove();
                $("#ctcPageCartItem" + productId).remove();
                $('#ctcTotalShippingCost').val('0.00');
                $('#ctcShippingcost').empty();
                $('#ctcPromoCodeSaving').val('0.00');
                $('#ctcSavingAfterPromoCode').empty();
                $('#ctcCheckOutPromoCode').val('');
                $('#ctcCheckoutPaymentOptions').hide();
                $('#ctcChooseShippingOptions span input').prop('checked', false);
                //calcualte and prepend grand total
                ctcCartCalculateTotal();
                //check for empty cart
                ctcCheckWidgetEmptyCart();
            }
            return false;
        });




        /**
         * 
         * 
         * script to set cart information in session storage and retrive it too
         * 
         * 
         * 
         * 
         */


        function setCtcCartLocalStorage(cartContent) {
            localStorage.removeItem("ctcWidgetCartData");
            localStorage.setItem("ctcWidgetCartData", cartContent);
        }



        //function to reinstate cookie on state change of the page
        function ctcReinstateCart() {
            var ctcCartData = localStorage.getItem("ctcWidgetCartData");
            if (ctcCartData) {
                $("#ctcProductCartWidget").empty();
                $(ctcCartData).hide().prependTo("#ctcProductCartWidget").show('slow').css('opacity', 1);
                //if product cart page add content to the page
                if ($('#ctcProductCartPageContent').length) {
                    ctcProductCartPage();
                }
            }
        }

        //script to deal with customer checkout
        $(document).on('click', '.ctcWidgetCartCheckOutbutton', function (event) {
            var data = {
                action: 'ctcCustomerWidgetCheckOut',
            }

            //alert(response);

            $.post(ctc_ajax_url, data, function (response) {

                if (JSON.parse(response)['notLoggedIn']) {
                    $.ctcOverlayEl({
                        modalMessage: JSON.parse(response)['notLoggedIn']
                    });
                }
            });
        });

        /**
         * 
         * script to show hide check out button
         * 
         */

        $(document).on('click', '#ctcCheckOutOptionStripe,#ctcCheckOutOptionCash', function () {
            var checkoutButton = $("#ctcCashCheckoutButton");
            if ($(this).attr('id') === 'ctcCheckOutOptionStripe') {
                $('#ctcCheckoutPaymentOptions button').attr('id', 'ctcStripeCheckoutButton').text('Pay with Card').show();
                $('#ctcStripeMountDiv,#card-errors').show();
            } else if ($(this).attr('id') === 'ctcCheckOutOptionCash') {
                $('#ctcStripeMountDiv,#card-errors').hide();
                $('#ctcCheckoutPaymentOptions button').attr('id', 'ctcCashCheckoutButton').text("Cash on Delivery").show();
            }

        });


        /**
         * 
         * Stripe payment script
         * 
         * 
         */
        if (document.getElementById('ctcCheckoutButton') !== null && 'undefined' !== typeof (ctcStripeParams)) {
            // Create a Stripe client.
            var stripe = Stripe(ctcStripeParams.ctcStripePubKey);
            // Create an instance of Elements.
            var elements = stripe.elements();
            // Custom styling can be passed to options when creating an Element.
            // (Note that this demo uses a wider set of styles than the guide below.)
            var style = {
                base: {
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    },
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };

            // Create an instance of the card Element.
            var card = elements.create('card', {
                style: style
            });

            // Add an instance of the card Element into the `card-element` <div>.
            card.mount('#ctcStripeMountDiv');

            // Handle real-time validation errors from the card Element.
            card.addEventListener('change', function (event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            // Handle form submission.
            document.getElementById('ctcProductCartPageForm').addEventListener('submit', function (event) {
                if ('none' !== document.getElementById('ctcStripeMountDiv').style.display) {
                    event.preventDefault();

                    stripe.createToken(card).then(function (result) {
                        if (result.error) {
                            // Inform the user if there was an error.
                            var errorElement = document.getElementById('card-errors');
                            errorElement.textContent = result.error.message;
                        } else {
                            // Send the token to your server.
                            // Insert the token ID into the form so it gets submitted to the server
                            var form = document.getElementById('ctcProductCartPageForm');
                            var hiddenInput = document.createElement('input');
                            hiddenInput.setAttribute('type', 'hidden');
                            hiddenInput.setAttribute('name', 'stripeToken');
                            hiddenInput.setAttribute('value', result.token.id);
                            form.appendChild(hiddenInput);

                            // Submit the form
                            form.submit();
                        }
                    });
                }
            });


        }
        /**
         * 
         * script to display and apply shipping cost and time
         * 
         * 
         */


        function ctcCalculateShippingTime(deliveryTime) {
            var timeNotice = '';
            if (deliveryTime == '1') {
                timeNotice = '<font> tomorrow';
            } else if (deliveryTime === '0') {
                timeNotice = 'today';
            } else {
                timeNotice = ' in ' + responseObj['deliveryTime'] + ' Days';
            }
            return timeNotice;
        }


        //function to check for empty field
        function ctcCheckEmptyShippingAddress() {
            var requiredField = ' ';
            $('#ctcShippingAddressZipCode,#ctcShippingAddress1,#ctcShippingAddressCity,#ctcShippingAddressStateProvince,#ctcShippingAddressCountry').each(function () {

                if ($(this).val() === '') {
                    $(this).css('border', '1px solid red');
                    requiredField = 'empty';
                } else {
                    $(this).removeAttr('style');
                }
            });
            if (requiredField == 'empty') {
                $.ctcOverlayEl({
                    modalMessage: 'Please fill in required fields, before proceeding.'
                });
                return false;
            } else {
                return true;
            }
        }

        //function to verify shipping address and calculate shipping cost
        function verifyAddressCalculateShippingCost(shippingRadio) {
            $.post(ctc_ajax_url, {
                'action': 'ctcGetUspsApiKey'
            }, function (uspsApiKey) {

                shippingStreet1 = $('#ctcShippingAddress1').val();
                shippingStreet2 = $('#ctcShippingAddress2').val();
                shippingCity = $('#ctcShippingAddressCity').val();
                shippingState = $('#ctcShippingAddressStateProvince').val();
                shippingZip = $('#ctcShippingAddressZipCode').val();
                shippingCountry = $('#ctcShippingAddressCountry').val();

                var uspsUrl = 'https://secure.shippingapis.com/ShippingAPI.dll';
                var validationData = 'API=Verify&XML=<AddressValidateRequest USERID="' + uspsApiKey + '">';
                validationData += '<Address>';
                validationData += '<Address1>' + shippingStreet1 + '</Address1>';
                validationData += '<Address2>' + shippingStreet2 + '</Address2>';
                validationData += '<City>' + shippingCity + '</City>';
                validationData += '<State>' + shippingState + '</State>';
                validationData += '<Zip5>' + shippingZip + '</Zip5> ';
                validationData += '<Zip4></Zip4>';
                validationData += '</Address>';
                validationData += '</AddressValidateRequest>';
                $.get(uspsUrl, validationData, function (response) {
                    var addressError = $(response).contents().find("Description").text();
                    if (addressError.length !== 0) {
                        $('#ctcCheckoutPaymentOptions,#ctcDisplayShippingCost').hide("normal");
                        $('.ctcChooseShippingOption').show("normal");
                        $('#ctcShippingOptionUsps').prop('checked', false);
                        $.ctcOverlayEl({
                            modalMessage: "Invalid Address, Please re-check your address again."
                        });

                        return false;
                    } else {

                        $('#ctcShippingAddressCity').val($(response).contents().find("Address2").text());
                        $('#ctcShippingAddressCity').val($(response).contents().find("City").text());
                        $('#ctcShippingAddressStateProvince').val($(response).contents().find("State").text());
                        $('#ctcShippingAddressZipCode').val($(response).contents().find("Zip5").text());
                        shippingCountry = $('#ctcShippingAddressCountry').val('USA');

                        var i = 0;
                        var productAndCount = {};
                        var productId = '';
                        $('.ctcPageCartProductId').each(function () {
                            productId = $(this).val();
                            productAndCount[productId] = $('#ctcProductCount-' + productId).val();
                            i++;
                        });

                        var data = {
                            'action': 'ctcCalculateShippingCost',
                            'shippingMethod': 'ctcUSPS',
                            'shippintZipCode': $('#ctcShippingAddressZipCode').val(),
                            'productAndCount': JSON.stringify(productAndCount),
                            'shipppingZipcode': $('#ctcShippingAddressZipCode').val()
                        }

                        //get data from server for calculation of shipping cost
                        $.post(ctc_ajax_url, data, function (shippingData) {
                            $.get(uspsUrl, shippingData, function (shippingCostInfo) {
                                var shippingTotal = 0;
                                $(shippingCostInfo).contents().find("Rate").each(function () {
                                    shippingTotal = (shippingTotal + parseFloat($(this).text()));
                                });
                                var shippingTime = $(shippingCostInfo).contents().find("MailService").text().split('&lt')[0];
                                $(".ctcCalculateShipingWait").animate({
                                    opacity: 0
                                }, 300, function () {
                                    $(this).remove()
                                });
                                $('#ctcTotalShippingCost').val(shippingTotal.toFixed(2));
                                $('#ctcShippingcost').empty().prepend(shippingTotal.toFixed(2) + ' <font > (' + shippingTime + ')</font>');
                                $('#customerShippingOptionInfo').val(shippingTime);
                                $('#ctcDisplayShippingCostInfo').show();
                                $('.ctcUspsShippingCostDisplay').show('normal');
                                $('#ctcCheckoutPaymentOptions,#ctcDisplayShippingCost').show("normal");
                                $('.ctcChooseShippingOption').hide("normal");
                                ctcCartCalculateTotal();
                            }).fail(function () {
                                shippingRadio.prop('checked', false);
                                $('#ctcCheckoutPaymentOptions,#ctcDisplayShippingCost').hide("medium");
                                $('#ctcUserShippingAddress').slideUp(1500);
                                alert("Shipping could not be calculated at this time \nPlease try again later");

                                $(".ctcCalculateShipingWait").animate({
                                    opacity: 0
                                }, 100, function () {
                                    $(this).remove()
                                });

                            });

                        }).fail(function () {
                            shippingRadio.prop('checked', false);
                            $('#ctcCheckoutPaymentOptions,#ctcDisplayShippingCost').hide("medium");
                            alert("Shipping could not be calculated at this time \nPlease try again later");
                            $('#ctcUserShippingAddress').slideUp(1500);
                            $(".ctcCalculateShipingWait").animate({
                                opacity: 0
                            }, 100, function () {
                                $(this).remove()
                            });
                        });

                    }
                }).fail(function () {
                    shippingRadio.prop('checked', false);
                    $('#ctcCheckoutPaymentOptions,#ctcDisplayShippingCost').hide("medium");
                    alert("Shipping could not be calculated at this time \nPlease try again later");
                    $('#ctcUserShippingAddress').slideUp(1500);
                    $(".ctcCalculateShipingWait").animate({
                        opacity: 0
                    }, 100, function () {
                        $(this).remove()
                    });
                });
            }).fail(function () {
                shippingRadio.prop('checked', false);
                $('#ctcCheckoutPaymentOptions,#ctcDisplayShippingCost').hide("medium");
                alert("Shipping could not be calculated at this time \nPlease try again later");
                $('#,#ctcUserShippingAddress').slideUp(1500);
                $(".ctcCalculateShipingWait").animate({
                    opacity: 0
                }, 100, function () {
                    $(this).remove()
                });

            });
        }


        //ajax for geting shipping cost and time
        $(document).on('click', '#ctcShippingOptionUsps,#ctcShippingOptionVendor,#ctcShippingOptionPickup', function () {
            var shippingRadio = $(this);
            switch ($(this).val()) {
                case 'ctcUSPS':
                    if (ctcCheckEmptyShippingAddress()) {
                        $("#ctcCashPayment").hide(1000);
                        $('#ctcCheckOutOptionStripe').prop('checked', true).parents('#ctcStripePayment').find('div').show();
                        $('#ctcCheckoutPaymentOptions button').attr('id', 'ctcStripeCheckoutButton').text('Pay with Card').show();
                        $(".ctcCalculateShipingWait").remove();
                        shippingRadio.parent().append('<font class="ctcCalculateShipingWait dashicons-before dashicons-update"></font>');
                        $('#ctcUserShippingAddress').slideDown(1500);
                        verifyAddressCalculateShippingCost(shippingRadio);
                    } else {
                        shippingRadio.prop('checked', false);
                        $('#ctcCheckoutPaymentOptions,#ctcDisplayShippingCost').hide("medium");
                        $('#ctcUserShippingAddress').slideDown(1500);
                        $('.ctcChooseShippingOption').show(1000);

                    }
                    break;

                case 'ctcVendorShipping':
                    if (ctcCheckEmptyShippingAddress()) {
                        $("#ctcCashPayment").show(1000);
                        $(".ctcCalculateShipingWait").animate({
                            opacity: 0
                        }, 300, function () {
                            $(this).remove()
                        });
                        shippingRadio.parent().append('<font class="ctcCalculateShipingWait dashicons-before dashicons-update"></font>');
                        $('#ctcUserShippingAddress').slideDown(1500);
                        $('#ctcCheckoutPaymentOptions,#ctcDisplayShippingCost').show(1000);
                        $('.ctcChooseShippingOption').hide(1000);

                        var totalProductCount = 0;
                        $('.ctcPageCartProductCount').each(function () {
                            totalProductCount = totalProductCount + parseInt($(this).val());
                        });
                        var data = {

                            'action': 'ctcCalculateShippingCost',
                            'shippingMethod': 'ctcVendorShipping',
                            'productCount': totalProductCount
                        }
                        $.post(ctc_ajax_url, data, function (response) {

                            $(".ctcCalculateShipingWait").animate({
                                opacity: 0
                            }, 300, function () {
                                $(this).remove()
                            });
                            responseObj = JSON.parse(response);

                            if (responseObj['deliveryCost'] == '0') {

                                var shippingTime = ctcCalculateShippingTime(responseObj['deliveryTime']);
                                $('#ctcTotalShippingCost').val(responseObj['deliveryCost'].toFixed(2));
                                $('#ctcShippingcost').animate({
                                    opacity: 0
                                }, 200, function () {
                                    $(this).empty()
                                }).animate({
                                    opacity: 1
                                }, 200, function () {

                                    $(this).prepend('<font>  Free shipping <font>' + shippingTime + '</font>');

                                });
                                $('#customerShippingOptionInfo').val('<font>  Delivers <font>' + shippingTime + '</font>');
                                $('#ctcDisplayShippingCostInfo').hide();

                            } else {
                                var shippingTime = ctcCalculateShippingTime(responseObj['deliveryTime']);

                                $('#ctcTotalShippingCost').val(responseObj['deliveryCost'].toFixed(2));
                                $('#ctcShippingcost').animate({
                                    opacity: 0
                                }, 200, function () {
                                    $(this).empty()
                                }).animate({
                                    opacity: 1
                                }, 200, function () {

                                    $(this).prepend(responseObj['deliveryCost'].toFixed(2) + '<font>( Delivers ' + shippingTime.replace('<font>', '') + ') </font> ');

                                });

                                $('#customerShippingOptionInfo').val('Delivers ' + shippingTime);
                                $('#ctcDisplayShippingCostInfo').show();
                            }
                            ctcCartCalculateTotal();
                        }).fail(function () {

                            shippingRadio.prop('checked', false);
                            $('#ctcCheckoutPaymentOptions,#ctcDisplayShippingCost').hide("normal");
                            alert("Shipping could not be calculated at this time \nPlease try again later");
                            $(".ctcCalculateShipingWait").animate({
                                opacity: 0
                            }, 100, function () {
                                $(this).remove()
                            });


                        });
                    } else {
                        shippingRadio.prop('checked', false);
                        $('#ctcCheckoutPaymentOptions,#ctcDisplayShippingCost').hide("medium");
                        $('#ctcUserShippingAddress').slideDown(1500);
                        $('.ctcChooseShippingOption').show(1000);

                    }

                    break;
                case 'ctcStorePickup':
                    $("#ctcCashPayment").show(1000);
                    $(".ctcCalculateShipingWait").animate({
                        opacity: 0
                    }, 100, function () {
                        $(this).remove()
                    });
                    shippingRadio.parent().append('<font class="ctcCalculateShipingWait dashicons-before dashicons-update"></font>');
                    $('#ctcUserShippingAddress').slideUp(2000).find('input').removeAttr('required');
                    $('#ctcCheckoutPaymentOptions,#ctcDisplayShippingCost').show(1500);
                    $('.ctcChooseShippingOption').hide("medium");

                    var data = {

                        'action': 'ctcCalculateShippingCost',
                        'shippingMethod': 'ctcStorePickup',
                        'productCount': totalProductCount
                    }

                    $.post(ctc_ajax_url, data, function (response) {
                        $(".ctcCalculateShipingWait").animate({
                            opacity: 0
                        }, 100, function () {
                            $(this).remove()
                        });
                        responseObj = JSON.parse(response);
                        var shippingTime = ctcCalculateShippingTime(responseObj['deliveryTime']);
                        $('#ctcShippingcost').animate({
                            opacity: 0
                        }, 200, function () {
                            $(this).empty()
                        }).animate({
                            opacity: 1
                        }, 200, function () {
                            $(this).prepend('<font class="ctcShippingPickupTime">You can pick it up ' + shippingTime);
                        });
                        $('#customerShippingOptionInfo').val('<font>You can pick it up ' + shippingTime);
                        $('#ctcDisplayShippingCostInfo').hide();
                    }).fail(function () {
                        shippingRadio.prop('checked', false);
                        $('#ctcCheckoutPaymentOptions,#ctcDisplayShippingCost').hide("medium");
                        $('#ctcUserShippingAddress').slideUp(1500);
                        alert("Shipping could not be calculated at this time \nPlease try again later");
                        $(".ctcCalculateShipingWait").animate({
                            opacity: 0
                        }, 100, function () {
                            $(this).remove()
                        });
                    });
                    $('#ctcTotalShippingCost').val('0.00');
                    ctcCartCalculateTotal();
                    break;
            }

        });

        /**
         * 
         * Script to apply promo code to the products
         * 
         */

        $(document).on('click', '#ctcApplyPromoCode', function () {
            var promoCodeButton = $(this);
            var productAndCount = {};
            var productId = '';
            $('.ctcPageCartProductId').each(function () {
                productId = $(this).val();
                productAndCount[productId] = $('#ctcProductCount-' + productId).val();
            });
            var data = {
                'action': 'ctcApplyPromocode',
                'productsAndCount': JSON.stringify(productAndCount),
                'promoCode': $('#ctcCheckOutPromoCode').val()
            }
            if (data['promoCode'].length != 0) {
                promoCodeButton.parent().addClass('ctcAjaxWaitPromoCode');
                $.post(ctc_ajax_url, data, function (response) {
                    promoCodeButton.parent().removeClass('ctcAjaxWaitPromoCode');
                    if (!isNaN(response)) {
                        $('#ctcPromoCodeSaving').val(parseFloat(response).toFixed(2));
                        $('#ctcSavingAfterPromoCode').empty().prepend('<span>Discount/Coupon saving : </span><span>' + addCommas(parseFloat(response).toFixed(2)) + '</span>');
                        ctcCartCalculateTotal();
                    } else if (response == 'invalidPromoCode') {
                        $.ctcOverlayEl({
                            modalMessage: ' Coupon code you have entered is invalid.'
                        });
                        $('#ctcCheckOutPromoCode').val('');

                    } else {
                        $('#ctcCheckOutPromoCode').val('');
                        $.ctcOverlayEl({
                            modalMessage: " Coupon does not apply to any of the products in cart."
                        });
                    }
                }).fail(function () {
                    alert(" Discount could not be calculated at this time \nPlease try again later");
                });
            }

        });

        /**
         * 
         * This section deals with thumbs and thumbs down rating
         * 
         * 
         */

        //function add substract thumup and thumbdown based on server respose
        function ctcAddSubstractThumbupThumbdon(productId, action, scenario) {
            switch (action) {
                case 'thumbsUp':
                    var ratingUpElem = $('.ctcThumbsUpCount-' + scenario + '-' + productId);
                    var ctcNewThumbsUpCount = parseInt(ratingUpElem.attr('data-type-thumupcount')) + 1;
                    ratingUpElem.empty().text(addCommas(ctcNewThumbsUpCount)).attr('data-type-thumupcount', ctcNewThumbsUpCount);
                    break;
                case 'thumbsDown':
                    ratingDownElem = $('.ctcThumbsDownCount-' + scenario + '-' + productId);
                    var ctcNewThumbsDownCount = parseInt(ratingDownElem.attr('data-type-thumdowncount')) + 1;
                    ratingDownElem.empty().text(addCommas(ctcNewThumbsDownCount)).attr('data-type-thumdowncount', ctcNewThumbsDownCount);
                    break;
                case 'thumbsUpReversed':
                    var ratingUpElem = $('.ctcThumbsUpCount-' + scenario + '-' + productId);
                    var ratingDownElem = $('.ctcThumbsDownCount-' + scenario + '-' + productId);
                    var ctcNewThumbsUpCount = parseInt(ratingUpElem.attr('data-type-thumupcount')) - 1;
                    var ctcNewThumbsDownCount = parseInt(ratingDownElem.attr('data-type-thumdowncount')) + 1;
                    ratingUpElem.empty().text(addCommas(ctcNewThumbsUpCount)).attr('data-type-thumupcount', ctcNewThumbsUpCount);
                    ratingDownElem.empty().text(addCommas(ctcNewThumbsDownCount)).attr('data-type-thumdowncount', ctcNewThumbsDownCount);
                    break;
                case 'thumbsDownReversed':
                    var ratingUpElem = $('.ctcThumbsUpCount-' + scenario + '-' + productId);
                    var ratingDownElem = $('.ctcThumbsDownCount-' + scenario + '-' + productId);
                    var ctcNewThumbsUpCount = parseInt(ratingUpElem.attr('data-type-thumupcount')) + 1;
                    var ctcNewThumbsDownCount = parseInt(ratingDownElem.attr('data-type-thumdowncount')) - 1;
                    ratingUpElem.empty().text(addCommas(ctcNewThumbsUpCount)).attr('data-type-thumupcount', ctcNewThumbsUpCount);
                    ratingDownElem.empty().text(addCommas(ctcNewThumbsDownCount)).attr('data-type-thumdowncount', ctcNewThumbsDownCount);
                    break;
                case 'unThumbsUp':
                    var ratingUpElem = $('.ctcThumbsUpCount-' + scenario + '-' + productId);
                    var ctcNewThumbsUpCount = parseInt(ratingUpElem.attr('data-type-thumupcount')) - 1;
                    ratingUpElem.empty().text(addCommas(ctcNewThumbsUpCount)).attr('data-type-thumupcount', ctcNewThumbsUpCount);;
                    break;
                case 'unThumbsDown':
                    var ratingDownElem = $('.ctcThumbsDownCount-' + scenario + '-' + productId);
                    var ctcNewThumbsDownCount = parseInt(ratingDownElem.attr('data-type-thumdowncount')) - 1;
                    ratingDownElem.empty().text(addCommas(ctcNewThumbsDownCount)).attr('data-type-thumdowncount', ctcNewThumbsDownCount);
                    break;
            }
        }


        //function to update Rating based on server response
        function ctcUpdateRatingBasedOnResult(serverResponse, productId, scenario) {
            switch (serverResponse) {
                case 'thumbsUp':
                    $('.ctcRating-' + productId + '-1').attr('title', 'You already thumbed up this product').animate({
                        'font-size': '25px'
                    }, 25, function () {
                        $(this).removeClass('ctcThumbDown').addClass('ctcUserThumbUp')
                    });
                    ctcAddSubstractThumbupThumbdon(productId, 'thumbsUp', scenario);
                    break;
                case 'thumbsDown':
                    $('.ctcRating-' + productId + '-2').attr('title', 'You already thumbed down this product').animate({
                        'font-size': '25px'
                    }, 25, function () {
                        $(this).removeClass('ctcThumbUp').addClass('ctcUserThumbDown')
                    });
                    ctcAddSubstractThumbupThumbdon(productId, 'thumbsDown', scenario);
                    break;
                case 'thumbsUpReversed':
                    $('.ctcRating-' + productId + '-1').attr('title', 'Thumbs Up').animate({
                        'font-size': '20px'
                    }, 25, function () {
                        $(this).removeClass('ctcUserThumbUp').addClass('ctcThumbUp')
                    });
                    $('.ctcRating-' + productId + '-2').attr('title', 'You already thumbed down this product').animate({
                        'font-size': '25px'
                    }, 25, function () {
                        $(this).removeClass('ctcThumbDown').addClass('ctcUserThumbDown')
                    });
                    ctcAddSubstractThumbupThumbdon(productId, 'thumbsUpReversed', scenario);
                    break;
                case 'thumbsDownReversed':
                    $('.ctcRating-' + productId + '-2').attr('title', 'Thumbs Down').animate({
                        'font-size': '20px'
                    }, 25, function () {
                        $(this).removeClass('ctcUserThumbDown').addClass('ctcThumbDown')
                    });
                    $('.ctcRating-' + productId + '-1').attr('title', 'You already thumbed up this product').animate({
                        'font-size': '25px'
                    }, 25, function () {
                        $(this).removeClass('ctcThumbUp').addClass('ctcUserThumbUp')
                    });
                    ctcAddSubstractThumbupThumbdon(productId, 'thumbsDownReversed', scenario);
                    break;
                case 'unThumbsUp':
                    ctcAddSubstractThumbupThumbdon(productId, 'unThumbsUp', scenario);
                    $('.ctcRating-' + productId + '-1').attr('title', 'Thumbs Up').animate({
                        'font-size': '20px'
                    }, 25, function () {
                        $(this).removeClass('ctcUserThumbUp').addClass('ctcThumbUp')
                    });
                    break;
                case 'unThumbsDown':
                    ctcAddSubstractThumbupThumbdon(productId, 'unThumbsDown', scenario);
                    $('.ctcRating-' + productId + '-2').attr('title', 'Thumbs Down').animate({
                        'font-size': '20px'
                    }, 25, function () {
                        $(this).removeClass('ctcUserThumbDown').addClass('ctcThumbDown')
                    });
                    break;
            }
        }

        //ajax function add thumbs up or thumbs down
        $(document).on('click', '.ctcThumbUp,.ctcThumbDown,.ctcUserThumbUp,.ctcUserThumbDown', function (event) {
            var scenario = $(this).attr('data-type-scenario');
            var data = {
                'action': 'ctcUserProductRating',
                'productId': $(this).attr('data-type-id'),
                'rating': $(this).attr('data-type-rating')
            }


            $.post(ctc_ajax_url, data, function (response) {
                if (response === 'notLoggedIn') {
                    $.ctcOverlayEl({
                        modalMessage: 'You need to log in to rate this product.'
                    });
                    event.preventDefault();
                    return false;
                } else {
                    ctcUpdateRatingBasedOnResult(response, data['productId'], scenario);
                }
            }).fail(function () {
                alert("Action could not be completed at this time \nPlease try again later");
            });

        });



        /*
         * 
         * section to sort products
         * 
         */

        //script to sort products
        $(document).on('change', '#ctcSortProductSelect', function () {

            if ($(this).val().length === 0) {
                return;
            }

            var parentContainer = $(this).attr('data-type-containertosort');
            var selecElement = $(this);
            var ajaxSort = $(this).attr('data-type-ajaxsort');
            var sortBy = $("option:selected", this).val();
            var sortableItems = $('>div', parentContainer);
            if (ajaxSort === "yes" && selecElement.attr('data-type-allproduct') === undefined) {

                $.post(ctc_ajax_url, {
                    'action': 'ctcAjaxSortProduct'
                }, function (response) {
                    $('#ctcSortProductSelect').parent().removeClass('ctcShowAjaxWait');
                    $(parentContainer).empty().prepend(response);
                    ctcSortProductBySelection(sortBy, parentContainer, $('>div', parentContainer));
                    selecElement.attr('data-type-allproduct', 'yes');
                    return false;
                }).fail(function () {
                    alert("Action could not be completed at this time \nPlease try again later");
                });
            } else {
                if (sortBy.length >= 1 && sortableItems.length >= 2) {
                    ctcSortProductBySelection(sortBy, parentContainer, sortableItems);
                }
            }

        });

        //function t0 switch among the products	to sort them
        function ctcSortProductBySelection(sortBy, parentContainer, sortableItems) {
            switch (sortBy) {
                case 'mostThumbUp':
                    var productArrayThumbUp = [];
                    sortableItems.each(function () {
                        var thisDiv = $(this);
                        productArrayThumbUp.push([thisDiv.attr('data-type-id'), parseInt(thisDiv.attr('data-type-thumbup')), thisDiv.wrap('<p/>').parent().html()]);
                    });
                    ctcSortArrayAndAppend(productArrayThumbUp, parentContainer, 'desc');
                    break;
                case 'priceLowest':
                    var productArrayPrice = [];
                    sortableItems.each(function () {
                        var thisDiv = $(this);
                        productArrayPrice.push([thisDiv.attr('data-type-id'), parseFloat(thisDiv.attr('data-type-price')), thisDiv.wrap('<p/>').parent().html()]);
                    });
                    ctcSortArrayAndAppend(productArrayPrice, parentContainer, 'asc')
                    break;
                case 'priceHighest':
                    var productArrayPrice = [];
                    sortableItems.each(function () {
                        var thisDiv = $(this);
                        productArrayPrice.push([thisDiv.attr('data-type-id'), parseFloat(thisDiv.attr('data-type-price')), thisDiv.wrap('<p/>').parent().html()]);

                    });
                    ctcSortArrayAndAppend(productArrayPrice, parentContainer, 'desc')
                    break;
                case 'addedDate':
                    var productArrayAddedDate = [];
                    sortableItems.each(function () {
                        var thisDiv = $(this);
                        productArrayAddedDate.push([thisDiv.attr('data-type-id'), parseInt(thisDiv.attr('data-type-dateadded')), thisDiv.wrap('<p/>').parent().html()]);
                    });

                    ctcSortArrayAndAppend(productArrayAddedDate, parentContainer, 'desc')
                    break;
            }

        }

        //function to sort products by thumb up
        function ctcSortArrayAndAppend(productArrayToSort, parentContainer, sortType) {
            let sortedHtml = Array();
            let sortByArray = Array();
            if (sortType == 'asc') {

                sortByArray = productArrayToSort.map(x => x[1]);
                sortByArray.sort((a, b) => a - b);
                sortedHtml = sortByArray.map(x => {

                    for (let i = 0; i < productArrayToSort.length; i++) {

                        if (x == productArrayToSort[i][1]) {
                            let minSortedArr = productArrayToSort.splice(i, 1);
                            return minSortedArr[0][2]
                        }
                    }
                });
            } else {
                sortByArray = productArrayToSort.map(x => x[1]);
                sortByArray.sort((a, b) => b - a);
                sortedHtml = sortByArray.map(x => {

                    for (let i = 0; i < productArrayToSort.length; i++) {

                        if (x == productArrayToSort[i][1]) {
                            let minSortedArr = productArrayToSort.splice(i, 1);
                            return minSortedArr[0][2]
                        }
                    }

                });

            }

            $(parentContainer).empty().html(sortedHtml.join(''));
            //lay in grid
            prodMas.layBrks(document.querySelector(parentContainer));
        }


        //script to load comment
        $(document).on('click', '#ctcLoadMoreReview', function () {
            var timeInterval;
            var loadReviewLink = $(this);
            var reviewOffSet = parseInt(loadReviewLink.attr('data-type-offset'));
            var totalReview = parseInt(loadReviewLink.attr('data-type-totalreview'));
            var data = {
                'action': 'ctcLoadMoreReview',
                'offSet': reviewOffSet,
                'postId': $(this).attr('data-type-postId')
            }
            $.post(ctc_ajax_url, data, function (response) {
                if ((reviewOffSet + 3) >= totalReview) {
                    loadReviewLink.remove();
                } else {
                    loadReviewLink.attr('data-type-offset', (reviewOffSet + 3));
                }
                var reviewHtml = [];
                $(response).filter('div').each(function (i) {
                    reviewHtml[i] = $(this).addClass('ctcSecondReview').html();
                });

                $('.ctcProductReviews').append(reviewHtml[0]);
                setTimeout(function () {
                    $('.ctcProductReviews').append(reviewHtml[1])
                }, 500);
                setTimeout(function () {
                    $('.ctcProductReviews').append(reviewHtml[2])
                }, 1000);
            }).fail(function () {
                alert("Action could not be completed at this time \nPlease try again later");
            });
        });


        //script load subcategories fro catgory widget
        $(document).on('click', '.ctcWidgetCategory', function () {
            var el = $(this).parent().children('ul');
            var categoryUrl = $(this).attr("data-category-url")
            var data = {
                'action': 'ctcWidgetLoadSubcategory',
                'categoryName': $(this).attr('data-product-categoryname')
            }
            if (el.children('li').length === 0) {
                $.post(ctc_ajax_url, data, function (response) {
                    if (response.length === 0) {
                        window.location.href = categoryUrl;
                    } else {
                        el.empty();
                        $(response).filter('li').each(function (i) {
                            var subCatHtml = '<li>' + $(this).html() + '</li>';
                            setTimeout(function () {
                                el.append(subCatHtml)
                            }, (600 * i));
                        });
                    }
                }).fail(function () {
                    alert("Action could not be completed at this time \nPlease try again later");
                });
            } else {
                el.slideToggle("slow");
            }
        });

        $('.ctcSingleProductGalleryContainer .gallery img').on('mouseenter', function () {
            $(".ctcProductProfileImage").css('background-image', 'url("' + $(this).attr('src') + '")');
        });


        if (undefined != document.querySelector('.ctcRatingAddtocartVideo')) {
            let container = document.querySelector('.ctcRatingAddtocartVideo')
            let contHeight = container.offsetHeight;
            let multiMedDivs = Array.from(container.querySelectorAll('.ctcMultiMedDiv'))

            for (let i in multiMedDivs) {
                contHeight -= (multiMedDivs[i].offsetHeight + 3);
            }
            container.style.paddingTop = (contHeight / 2) + 'px';
        }
        /**
         * 
         * do not write code beyond 
         * 
         */

    });
}(jQuery));