"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_Pos_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Pos.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Pos.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
/* harmony import */ var _Layouts_pos_Header__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/Layouts/pos/Header */ "./resources/js/Layouts/pos/Header.vue");
/* harmony import */ var _components_Ui_PosProductLoadingSkeleton__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/components/Ui/PosProductLoadingSkeleton */ "./resources/js/components/Ui/PosProductLoadingSkeleton.vue");
/* harmony import */ var _components_Ui_OverlayLoading__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/components/Ui/OverlayLoading */ "./resources/js/components/Ui/OverlayLoading.vue");
/* harmony import */ var _components_inputs_KeyboardNumberPicker__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @/components/inputs/KeyboardNumberPicker */ "./resources/js/components/inputs/KeyboardNumberPicker.vue");
/* harmony import */ var _components_Ui_LoadingButton__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @/components/Ui/LoadingButton */ "./resources/js/components/Ui/LoadingButton.vue");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//






/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: ['customerTypes'],
  components: {
    Header: _Layouts_pos_Header__WEBPACK_IMPORTED_MODULE_0__["default"],
    PosProductLoadingSkeleton: _components_Ui_PosProductLoadingSkeleton__WEBPACK_IMPORTED_MODULE_1__["default"],
    OverlayLoading: _components_Ui_OverlayLoading__WEBPACK_IMPORTED_MODULE_2__["default"],
    KeyboardNumberPicker: _components_inputs_KeyboardNumberPicker__WEBPACK_IMPORTED_MODULE_3__["default"],
    LoadingButton: _components_Ui_LoadingButton__WEBPACK_IMPORTED_MODULE_4__["default"]
  },
  data: function data() {
    return {
      customerLoading: false,
      productLoading: true,
      overlayLoading: false,
      submitCutomerLoading: false,
      page: 1,
      perPage: 20,
      totalItems: 0,
      links: {
        next: null,
        previous: null
      },
      selected: {
        category: null,
        products: [],
        customer: null
      },
      field: {
        search: {
          customer_name: ""
        },
        customer: {
          name: "",
          client_type: "",
          local_address: "",
          phone: "",
          email: "",
          district: "",
          province: "",
          errors: null
        }
      }
    };
  },
  computed: _objectSpread(_objectSpread({}, (0,vuex__WEBPACK_IMPORTED_MODULE_5__.mapGetters)(["products", "categories", "districts", "provinces", "customers"])), {}, {
    totalSelectedProductPrice: function totalSelectedProductPrice() {
      var sum = 0;
      this.selected.products.forEach(function (product) {
        sum = sum + product.product_price * product.quantity;
      }); // return this.selected.products.reduce( function(a, b){
      //     return a + (b['product_price'] * b['quantity]);
      // }, 0);

      return sum;
    }
  }),
  watch: {
    'field.search.customer_name': function fieldSearchCustomer_name(newVal, oldVal) {
      console.log("the customer name is", newVal);
      if (!newVal) return;
      this.fetchCustomerList();
    },
    'selected.category': function selectedCategory(newVal, oldVal) {
      this.overlayLoading = true;
      this.page = 1;
      this.totalItems = 0;
      this.links.next = null;
      this.links.previous = null;
      this.fetchProductList();
    },
    'field.customer.province': function fieldCustomerProvince(newVal, oldVal) {
      if (!newVal) return;
      this.fetchDistrictList();
    }
  },
  mounted: function mounted() {
    this.fetchCategoryList();
    this.fetchProductList();
    this.fetchProvienceList();
  },
  methods: {
    addSelectedCustomer: function addSelectedCustomer(customer) {
      this.selected.customer = customer;
      this.field.search.customer_name = null;
    },
    removeSelectedCustomer: function removeSelectedCustomer() {
      this.selected.customer = null;
    },
    addSelectedProductItem: function addSelectedProductItem(product) {
      var item = {
        id: product.id,
        product_name: product.product_name,
        product_code: product.product_code,
        product_price: product.product_price,
        total_quantity: 1
      };
      this.selected.products.push(item);
    },
    removeSelectedProductItem: function removeSelectedProductItem(index) {
      this.selected.products.splice(index, 1);
    },
    nextProductList: function nextProductList() {
      this.page = this.page + 1;
      this.overlayLoading = true;
      this.fetchProductList();
    },
    previousProductList: function previousProductList() {
      this.page = this.page - 1;
      this.overlayLoading = true;
      this.fetchProductList();
    },
    fetchCategoryList: function fetchCategoryList() {
      this.$store.dispatch('GET_CATEGORY_LIST').then(function (response) {// console.log("the category is ", this.categories);
      });
    },
    fetchProductList: function fetchProductList() {
      var _this = this;

      var params = {
        'include': '',
        'per_page': this.perPage,
        'page': this.page
      };

      if (this.selected.category) {
        params.category_id = this.selected.category;
      }

      this.$store.dispatch('GET_PRODUCT_LIST', {
        params: params
      }).then(function (response) {
        var _response$data$meta, _response$data$links, _response$data$links2;

        _this.totalItems = (_response$data$meta = response.data.meta) === null || _response$data$meta === void 0 ? void 0 : _response$data$meta.total;
        _this.links.next = (_response$data$links = response.data.links) === null || _response$data$links === void 0 ? void 0 : _response$data$links.next;
        _this.links.previous = (_response$data$links2 = response.data.links) === null || _response$data$links2 === void 0 ? void 0 : _response$data$links2.prev;
        _this.productLoading = false;
        _this.overlayLoading = false;
      });
    },
    fetchProvienceList: function fetchProvienceList() {
      this.$store.dispatch('GET_PROVINCE_LIST').then(function (response) {// console.log("the provience is ", this.provinces);
      });
    },
    fetchDistrictList: function fetchDistrictList() {
      this.$store.dispatch('GET_DISTRICT_LIST', {
        params: {
          district_id: this.field.customer.district
        }
      }).then(function (response) {// console.log("the district is ", this.districts);
      });
    },
    fetchCustomerList: function fetchCustomerList() {
      var _this2 = this;

      this.customerLoading = true;
      this.$store.dispatch('GET_CUSTOMER_LIST', {
        params: {
          name: this.field.search.customer_name
        }
      }).then(function (response) {
        _this2.customerLoading = false;
        console.log("the cutomers is ", _this2.customers);
      });
    },
    actionCreateCustomer: function actionCreateCustomer() {
      var _this3 = this;

      this.submitCutomerLoading = true;
      this.$store.dispatch('CREATE_CUSTOMER', this.field.customer).then(function (response) {
        _this3.resetCustomerFormFields();

        $('#customerModal').modal('hide');
        _this3.selected.customer = response.data.data;
        _this3.submitCutomerLoading = false;
        alert("Customer Added Successfully");
      })["catch"](function (err) {
        _this3.submitCutomerLoading = false;

        if (err.response.status === 422) {
          _this3.field.customer.errors = err.response.data.errors;
        }
      });
    },
    actionSuspendProductItem: function actionSuspendProductItem() {
      var _this4 = this;

      if (this.selected.products.length <= 0) {
        this.$swal.fire({
          title: "Please add product before suspending the sale. Thank you!",
          showDenyButton: false,
          confirmButtonText: "Ok"
        });
        return;
      }

      if (!this.selected.customer) {
        this.$swal.fire({
          title: "Please select customer before suspending sale. Thank you!",
          showDenyButton: false,
          confirmButtonText: "Ok"
        });
        return;
      }

      this.$swal.fire({
        title: "Are you sure you want to suspend sale?",
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: "Sure",
        denyButtonText: "Cancle"
      }).then(function (result) {
        if (result.isConfirmed) {
          _this4.overlayLoading = true;

          _this4.$store.dispatch('SUSPEND_SALE', {
            'customer_id': _this4.selected.customer.id,
            'products': _this4.selected.products,
            'discount': 0
          }).then(function (response) {
            _this4.overlayLoading = false;
            window.location.href = '/suspendedsale';
          });
        }
      });
    },
    resetCustomerFormFields: function resetCustomerFormFields() {
      this.field.customer.client_type = "";
      this.field.customer.name = "";
      this.field.customer.email = "";
      this.field.customer.phone = "";
      this.field.customer.local_address = "";
      this.field.customer.provience = "";
      this.field.customer.district = "";
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/LoadingButton.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/LoadingButton.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    title: {
      type: String,
      "default": 'BUTTON'
    },
    loading: {
      type: Boolean,
      "default": false
    },
    type: {
      type: String,
      "default": "button"
    },
    style_: Object
  },
  created: function created() {
    console.log("the loading is ", this.loading);
  },
  methods: {
    submit: function submit() {
      this.$emit('load');
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/OverlayLoading.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/OverlayLoading.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    loading: {
      type: Boolean,
      "default": true
    },
    text: {
      type: String,
      "default": 'Loading...'
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/inputs/KeyboardNumberPicker.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/inputs/KeyboardNumberPicker.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {};
  },
  mounted: function mounted() {
    jQuery(document).ready(function ($) {
      $('.keyboard').keyboard({
        layout: 'num',
        restrictInput: true,
        // Prevent keys not in the displayed keyboard from being typed in
        preventPaste: true,
        // prevent ctrl-v and right click
        autoAccept: true,
        alwaysOpen: false,
        usePreview: false
      });
    });
  },
  methods: {
    alert: function (_alert) {
      function alert(_x) {
        return _alert.apply(this, arguments);
      }

      alert.toString = function () {
        return _alert.toString();
      };

      return alert;
    }(function (element) {
      alert("hello world");
    })
  }
});

/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[3]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/PosProductLoadingSkeleton.vue?vue&type=style&index=0&id=b4de3684&lang=scss&scoped=true&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[3]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/PosProductLoadingSkeleton.vue?vue&type=style&index=0&id=b4de3684&lang=scss&scoped=true& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".post-card__header[data-v-b4de3684] {\n  min-width: 100%;\n  height: 68px;\n  background-color: #ddd;\n  -webkit-animation: pulse-bg-data-v-b4de3684 1.2s ease-in-out infinite;\n          animation: pulse-bg-data-v-b4de3684 1.2s ease-in-out infinite;\n}\n.post-card__body[data-v-b4de3684] {\n  border-bottom: 1px solid #ccc;\n}\n.post-card .user_image[data-v-b4de3684] {\n  margin-right: 10px;\n}\n.post-card .user_image img[data-v-b4de3684] {\n  width: 32px;\n  height: 32px;\n  border-radius: 100%;\n  border: 1px solid #ccc;\n  overflow: hidden;\n  max-width: 36px;\n  background-color: #ddd;\n  -webkit-animation: pulse-bg-data-v-b4de3684 1.2s ease-in-out infinite;\n          animation: pulse-bg-data-v-b4de3684 1.2s ease-in-out infinite;\n}\n.post-card .user_name[data-v-b4de3684] {\n  min-width: 100px;\n  height: 16px;\n  background-color: #ddd;\n  -webkit-animation: pulse-bg-data-v-b4de3684 1.2s ease-in-out infinite;\n          animation: pulse-bg-data-v-b4de3684 1.2s ease-in-out infinite;\n}\n.post-card .tag[data-v-b4de3684] {\n  min-width: 100px;\n  height: 16px;\n  background-color: #ddd;\n  -webkit-animation: pulse-bg-data-v-b4de3684 1.2s ease-in-out infinite;\n          animation: pulse-bg-data-v-b4de3684 1.2s ease-in-out infinite;\n}\n.post-card__date[data-v-b4de3684] {\n  min-width: 100px;\n  height: 16px;\n  background-color: #ddd;\n  -webkit-animation: pulse-bg-data-v-b4de3684 1.2s ease-in-out infinite;\n          animation: pulse-bg-data-v-b4de3684 1.2s ease-in-out infinite;\n}\n.post-card__title[data-v-b4de3684] {\n  min-width: 100%;\n  height: 24px;\n  background-color: #ddd;\n  -webkit-animation: pulse-bg-data-v-b4de3684 1.2s ease-in-out infinite;\n          animation: pulse-bg-data-v-b4de3684 1.2s ease-in-out infinite;\n}\n@-webkit-keyframes pulse-bg-data-v-b4de3684 {\n0% {\n    background-color: #ddd;\n}\n50% {\n    background-color: #d0d0d0;\n}\n100% {\n    background-color: #ddd;\n}\n}\n@keyframes pulse-bg-data-v-b4de3684 {\n0% {\n    background-color: #ddd;\n}\n50% {\n    background-color: #d0d0d0;\n}\n100% {\n    background-color: #ddd;\n}\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Pos.vue?vue&type=style&index=0&lang=css&":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Pos.vue?vue&type=style&index=0&lang=css& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_assets_css_pos_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! -!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../assets/css/pos.css */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./resources/js/assets/css/pos.css");
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_assets_css_pos_responsive_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! -!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../assets/css/pos_responsive.css */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./resources/js/assets/css/pos_responsive.css");
// Imports



var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
___CSS_LOADER_EXPORT___.i(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_assets_css_pos_css__WEBPACK_IMPORTED_MODULE_1__["default"]);
___CSS_LOADER_EXPORT___.i(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_assets_css_pos_responsive_css__WEBPACK_IMPORTED_MODULE_2__["default"]);
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n.loading{\n    display: flex;\n    align-items: center;\n    justify-content: center;\n}\n.selected-input-box{\n    display: block;\n    width: 100%;\n    height: calc(2.25rem + 2px);\n    padding: .375rem .75rem;\n    font-size: 1rem;\n    font-weight: 400;\n    line-height: 1.5;\n    color: #495057;\n    background-color: #fff;\n    background-clip: padding-box;\n    border: 1px solid #ced4da;\n    border-radius: .25rem;\n    box-shadow: inset 0 0 0 transparent;\n    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;\n}\n.selected-input-box > span {\n}\n.selected-input-box .close-icon {\n    cursor: pointer;\n}\n.customer-search-box{\n    position: relative;\n}\n.customer-search-box .search-box{\n    position: absolute;\n    width: 100%;\n    z-index: 100;\n    max-height: 300px;\n    overflow-x: hidden;\n    overflow-y: auto;\n}\n.search-customer__list .search-customer__list-item:hover,\n.search-customer__list .search-customer__list-item:focus\n{\n    cursor: pointer;\n    color: #dc3f26;\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/LoadingButton.vue?vue&type=style&index=0&id=2d71081e&scoped=true&lang=css&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/LoadingButton.vue?vue&type=style&index=0&id=2d71081e&scoped=true&lang=css& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n.btn_submit[data-v-2d71081e]{\n    padding-right: 30px;\n    padding-left: 30px;\n    color:#ffffff;\n    /* display: flex;*/\n    /* align-items: center;*/\n    border-radius: 0 !important;\n    width: inherit;\n    text-align: center;\n    text-transform: uppercase;\n}\n.btn_submit.disabled[data-v-2d71081e]{\n    cursor: not-allowed;\n    pointer-events: none;\n    color: #fff;\n    background-color: #007bff;\n    border-color: #007bff;\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/OverlayLoading.vue?vue&type=style&index=0&id=3723dd28&scoped=true&lang=css&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/OverlayLoading.vue?vue&type=style&index=0&id=3723dd28&scoped=true&lang=css& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n.overlay[data-v-3723dd28]{\n    background-color: rgba(0,0,0,0.5);\n    position: fixed;\n    width: 100%;\n    height: 100vh;\n    display: flex;\n    justify-content: center;\n    align-items: center;\n    z-index: 10000;\n    top:0;\n    bottom: 0;\n}\n.overlay .content[data-v-3723dd28] {\n    display: flex;\n    align-items: center;\n    justify-content: center;\n    padding: 0 10px;\n}\n.overlay .spinner-border[data-v-3723dd28]{\n    height: 10px;\n    width: 10px;\n}\n.bg-primary[data-v-3723dd28]{\n    background: #dc3f26;\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./resources/js/assets/css/pos.css":
/*!*******************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./resources/js/assets/css/pos.css ***!
  \*******************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "* {\n    padding: 0;\n    margin: 0;\n    box-sizing: border-box;\n}\n\nbody {\n    padding: 0;\n    margin: 0;\n    font-family: 'Roboto', sans-serif;\n    font-size: 15px;\n    background: #f3f3f3;\n}\n\np {\n    font-size: 16px;\n    line-height: 26px;\n    color: #616161;\n}\n\nul {\n    margin: 0;\n    padding: 0;\n}\n\nli {\n    list-style: none;\n}\n\nimg {\n    width: 100%;\n}\n\nh1,\nh2,\nh3,\nh4,\nh5,\nh6 {\n    margin-top: 0;\n    padding: 0;\n    font-weight: bold;\n    color:#363636;\n}\n\nh1 {\n    font-size: 40px;\n    line-height: 48px;\n}\n\nh2 {\n    font-size: 40px;\n    line-height: 47px;\n}\n\nh3 {\n    font-size: 30px;\n    line-height: 35px;\n}\n\nh4 {\n    font-size: 18px;\n    line-height: 24px;\n}\n\nh5 {\n    font-size: 14px;\n    line-height: 24px;\n}\n\na {\n    color: #0a58e7;\n    display: block;\n    text-decoration: none !important;\n    transition: ease-in-out .3s;\n}\n\na:hover {\n    color: #EF360F;\n}\n\n.mt {\n    margin-top: 80px;\n}\n\n.mb {\n    margin-bottom: 80px;\n}\n\n.pt {\n    padding-top: 80px;\n}\n\n.pb {\n    padding-bottom: 80px;\n}\n\n.m-10 {\n    margin-left: -10px;\n    margin-right: -10px;\n}\n\n.p-10 {\n    padding-left: 10px;\n    padding-right: 10px;\n}\n\n.form-control:focus {\n    outline: none;\n    box-shadow: none;\n    border: 1px solid #f94944;\n}\n\n.container {\n    max-width: 1300px;\n}\n\n\n\n\n\n/* Header */\n#header {\n    background: #ffffff;\n    box-shadow: 0 6px 20px 0 rgb(0 0 0 / 10%);\n    position: fixed;\n    width: 100%;\n    top: 0;\n    z-index: 991;\n}\n.h-wrap {\n    display: flex;\n    align-items: center;\n    justify-content: space-between;\n}\n.time span {\n    display: block;\n    color: #5c5c5c;\n    font-size: 14px;\n}\n.header-right {\n    display: flex;\n    align-items: center;\n}\n\n.header-btns {\n    display: flex;\n    align-items: center;\n}\n\n.main-btns {\n    display: flex;\n    align-items: center;\n}\n.navbar-light .navbar-nav .nav-link{\n    color: #5c5c5c;\n    padding: 20px 15px;\n    font-size: 14px;\n}\n.navbar-light .navbar-nav .nav-link:focus{\n    color:#5c5c5c;\n}\n.navbar-light .navbar-nav .nav-link:hover{\n    color: #dc3f26;\n}\n.navbar{\n    padding: 0;\n}\n.navigation {\n    border-right: 1px solid #ffffff2e;\n    margin-right: 15px;\n}\n.main-btns{\n    margin-right: 15px;\n}\n.main-btns a {\n    display: block;\n    background-color: #dc3f26;\n    background-image: linear-gradient(to bottom, #dc3f26, #dc3f26);\n    padding: 5px 10px;\n    border-radius: 3px;\n    color: #fff;\n    text-shadow: 1px 1px 0 rgb(0 0 0 / 20%);\n    box-shadow: inset 0 1px 0 rgb(255 255 255 / 20%), 0 1px 2px rgb(0 0 0 / 5%);\n    border: 1px solid;\n    border-color: #dc3f26;\n    font-size: 14px;\n}\n.main-btns a:first-child {\n    background: #51a351;\n    border-color: #51a351;\n}\n.main-btns a:hover{\n    background: #165285;\n    border-color:#165285 ;\n}\n.main-btns a +a{\n    margin-left: 10px;\n}\n.dropdown-menu {\n    padding: 0;\n    border-radius: 0;\n    box-shadow: 0px 2px 10px rgb(0 0 0 / 20%);\n    border: none;\n    margin: 0;\n    right: 0;\n}\n\n.dropdown-menu li a {\n    font-size: 15px;\n    color: #747474;\n    padding: 8px 15px;\n}\n.dropdown-menu li a:hover{\n    background:#dc3f26;\n    color: #fff;\n}\n\n.dropdown-menu li+li a {\n    border-top: 1px solid #efefef;\n}\n.accounts .navbar-light .navbar-nav .nav-link{\n    border-left: 1px solid #ffffff2e;\n}\n.logo img {\n    max-width: 250px;\n}\n.pos-sidebar {\n    background: #fff;\n    padding: 20px;\n    border-radius: 4px;\n    box-shadow: 0px 2px 10px rgba(0,0,0,0.20);\n    position: sticky;\n    top: 67px;\n    z-index: 980;\n}\n\n.pos-main-section {\n    padding: 15px 0;\n    margin-top: 67px;\n}\n.pos-sidebar .form-control {\n    height: 34px;\n    border-radius: 3px;\n    font-size: 13px;\n    border: 1px solid #e5e5e5;\n}\n\n.input-btn .btn {\n    height: 34px;\n    padding: 5px 10px;\n    font-size: 17px;\n    background: #dc3f26;\n    border: none;\n    border-radius: 3px;\n}\n.pos-sidebar .form-group {\n    margin-bottom: 10px;\n}\n/* Header End */\n\n\n\n\n\n/* Mobile Menu */\n#mySidenav {\n    position: fixed;\n    right: 0;\n    top: 0;\n    bottom: 0;\n    height: 100%;\n    z-index: 1020;\n    background: #fff;\n    width: 280px;\n    height: 100%;\n    overflow-x: hidden;\n}\n\n.sidenav {\n    margin-right: -280px;\n    transition: ease-in-out .3s;\n    opacity: 0;\n    visibility: hidden;\n}\n\n.sidenav.active {\n    margin-right: 0;\n    box-shadow: -5px 0px 15px 0 rgb(0 0 0 / 15%);\n    opacity: 1;\n    visibility: visible;\n}\n\n#menu1 li a {\n    display: block;\n    padding: 15px 20px;\n    color: #5c5c5c;\n    border-top: 1px solid #efefef;\n    transition: ease-in-out .3s;\n    font-weight: 600;\n    text-transform: capitalize;\n    font-size: 15px;\n}\n\n#menu1 li a:hover {\n    color: #ff0000;\n}\n\n.mobile-logo {\n    display: flex;\n    align-items: center;\n    justify-content: space-between;\n    padding: 15px 20px;\n    background: #fff;\n    box-shadow: 0 6px 20px 0 rgb(0 0 0 / 10%);\n    position: sticky;\n    top: 0;\n    background: #fff;\n    z-index: 960;\n}\n.mobile-logo img {\n    max-width: 130px;\n}\n\n#close-btn {\n    background: #d93e26;\n    color: #fff;\n    height: 30px;\n    width: 30px;\n    text-align: center;\n    line-height: 32px;\n    font-size: 22px;\n    border-radius: 100%;\n}\n\n#mySidenav::-webkit-scrollbar {\n    width: 7px;\n}\n\n#mySidenav::-webkit-scrollbar-track {\n    background: #fff;\n    display: none;\n}\n\n#mySidenav::-webkit-scrollbar-thumb {\n    background: rgb(208 208 208);\n    border-radius: 10px;\n}\n\n.mobile-only {\n    display: none;\n}\n.toggle-btn span {\n    display: block;\n    height: 5px;\n    background: #dc3f26;\n    margin: 3px;\n    width: 5px;\n}\n.toggle-wrap {\n    display: flex;\n}\n.toggle-btn {\n    display: none;\n    cursor: pointer;\n}\n.header-mobile {\n    display: none;\n}\n/* Mobile Menu End */\n\n\n\n\n\n\n/* Pos Main Part */\n.pos-sidebar table th {\n    padding: 5px;\n    vertical-align: middle;\n    background: #165285;\n    color: #fff;\n    border-color: #e5e5e5;\n    text-align: center;\n    font-size: 15px;\n    font-weight: 500;\n}\n\n.pos-sidebar table td {\n    padding: 5px;\n    font-size: 13px;\n    border-color: #e5e5e5;\n    text-align: center;\n    color: #5c5c5c;\n}\n.pos-sidebar table td a{\n    color: #5c5c5c;\n}\n.pos-sidebar table td a:hover{\n    color: #dc3f26;\n}\n.pos-sidebar table td i {\n    font-size: 20px;\n}\n\n.pos-sidebar table th i {\n    font-size: 20px;\n}\n\n.pos-sidebar table tr {\n    border-color: #e5e5e5;\n}\n.pos-sidebar input[type=\"number\"] {\n    border: none;\n    width: 40px;\n    text-align: center;\n}\n.trash {\n    padding: 0;\n    background: transparent;\n    border: none;\n    color: #5c5c5c;\n}\n.trash:hover{\n    color: #dc3f26;\n}\n.pos-sidebar table td b {\n    color: black;\n}\n.butk-btns {\n    display: flex;\n    justify-content: space-between;\n    align-items: center;\n}\n\n.butk-btns .btn {\n    flex: auto;\n    border: none;\n    padding: 6px 10px;\n    text-transform: uppercase;\n    font-size: 14px;\n    font-weight: 500;\n    border-radius: 3px;\n    box-shadow: inset 0 1px 0 rgb(255 255 255 / 20%), 0 1px 2px rgb(0 0 0 / 5%);\n    border: 1px solid;\n    border-color: #dc3f26;\n}\n\n.butk-btns .btn +.btn {\n    margin-left: 10px;\n}\n\n.pay-btn .btn {\n    width: 100%;\n    padding: 8px 10px;\n    border: none;\n    text-transform: uppercase;\n    font-size: 14px;\n    font-weight: 500;\n    background: #dc3f26;\n    box-shadow: inset 0 1px 0 rgb(255 255 255 / 20%), 0 1px 2px rgb(0 0 0 / 5%);\n    border: 1px solid;\n    border-color: #dc3f26;\n}\n.pay-btn {\n    margin-top: 10px;\n}\n\n.butk-btns {\n    margin-top: 10px;\n}\n.butk-btns .btn.btn-danger{\n    border-color: #dc3545;\n}\n.butk-btns .btn.btn-success{\n    border-color: #198754;\n}\n.butk-btns .btn.btn-warning{\n    border-color: #ffc107;\n}\n.pos-main .nav-tabs .nav-link {\n    background: #fff;\n    border: 1px solid #e1e1e1 !important;\n    padding: 6px 15px;\n    color: #5c5c5c;\n    border-radius: 40px;\n    width: 100%;\n    transition: ease-in-out .3s;\n    min-width: 113px;\n}\n.pos-main .nav-tabs .nav-link.active{\n    background: #dc3f26;\n    border-color: #dc3f26 !important;\n    color: #fff;\n}\n.pos-main .nav-tabs .nav-link:hover{\n    background: #dc3f26;\n    border-color:#dc3f26 !important;\n    color: #fff;\n}\n.pos-main .nav-tabs {\n    border-bottom: none;\n    margin-bottom: 15px;\n    background: #fff;\n    padding: 10px;\n    border-radius: 4px;\n    box-shadow: 0px 0px 10px rgb(0 0 0 / 20%);\n    position: sticky;\n    top: 65px;\n    z-index: 970;\n    background: #fff;\n    flex-wrap: inherit;\n    overflow-y: hidden;\n    overflow-x: scroll;\n}\n.nav-tabs::-webkit-scrollbar {\n  height: 4px;\n}\n.nav-tabs:hover::-webkit-scrollbar{\n    display: block;\n}\n.nav-tabs::-webkit-scrollbar-track {\n  background: #f1f1f1;\n}\n\n.nav-tabs::-webkit-scrollbar-thumb {\n  background: #888;\n  border-radius: 30px;\n}\n.pos-main .nav-item {\n    padding: 0 5px;\n    width: 20%;\n}\n.pos-product-wrap {\n    text-align: center;\n    background: #fff;\n    padding: 10px;\n    border-radius: 3px;\n    box-shadow: 0px 0px 10px rgb(0 0 0 / 20%);\n    margin-bottom: 10px;\n}\n\n.pos-product-wrap button {\n    border: none;\n    background: transparent;\n}\n.pos-main .padding {\n    padding-left: 5px;\n    padding-right: 5px;\n}\n\n.pos-main .margin {\n    margin-left: -5px;\n    margin-right: -5px;\n}\n.pos-product-content span {\n    display: block;\n    font-size: 13px;\n    line-height: 18px;\n    margin-top: 10px;\n}\n.pos-product-wrap:hover .pos-product-content span{\n    color: #dc3f26;\n}\n.border-input {\n    border: 1px solid lightgrey !important;\n    border-radius: 3px;\n    width: 70px !important;\n}\n.page-link {\n    height: 60px;\n    width: 60px;\n    line-height: 60px;\n    text-align: center;\n    padding: 0;\n    font-size: 40px;\n    background: #165285;\n    color: #fff;\n    border-radius: 3px !important;\n}\n.page-link:hover{\n    background: #dc3f26;\n    color: #fff;\n}\n.page-link:focus{\n    background: #dc3f26;\n    color: #fff;\n}\n.page-link +.page-link {\n}\n\n.pagination li+li {\n    margin-left: 10px;\n}\n.pagination{\n    margin-top: 5px;\n}\n.main-btns a i {\n    font-size: 20px;\n    vertical-align: middle;\n    margin-right: 2px;\n}\n.pos-sidebar .padding {\n    padding-left: 5px;\n    padding-right: 5px;\n}\n\n.pos-sidebar .margin {\n    margin-left: -5px;\n    margin-right: -5px;\n}\n.input-btn {\n    text-align: center;\n}\n.ui-keyboard-input {\n    text-align: center;\n    width: 50px;\n    border: none;\n}\n.modal-header {\n    padding: 12px 30px;\n    background: #165285;\n}\n\n.modal-body {\n    padding: 25px;\n}\n\n.modal-header h5 {\n    color: #fff;\n    font-size: 18px;\n    font-weight: 500;\n}\n\n.btn-close {\n    color: #fff;\n    opacity: 1;\n    background-color: #fff;\n    border-radius: 100%;\n}\n\n.modal-form label {\n    font-size: 15px;\n    font-weight: 600;\n    margin-bottom: 5px;\n}\n\n.modal-form .form-control {\n    height: 34px;\n    font-size: 14px;\n    border: 1px solid #dbdbdb;\n}\n\n.modal-form .form-group {\n    margin-bottom: 15px;\n}\n\n.modal-form button {\n    background: #dc3f26;\n    color: #fff;\n    padding: 10px 20px;\n    text-transform: uppercase;\n    font-size: 14px;\n    font-weight: 500;\n    border: none;\n    width: 100%;\n}\n.ui-keyboard-cancel {\n    background: #dc3f26 !important;\n    border-color: #dc3f26 !important;\n}\n.ui-keyboard-accept{\n    background: #51a351 !important;\n    border-color: #51a351 !important;\n}\n.navbar-nav .dropdown-menu{\n    position: absolute;\n}\n.hr-mobile {\n    display: none;\n}\n.pos-sidebar .first-table {\n    overflow-y: scroll;\n    overflow-x: hidden;\n    max-height: 237px;\n    margin-bottom: 15px;\n}\n.pos-sidebar .first-table::-webkit-scrollbar {\n  width: 2px;\n}\n.pos-sidebar .first-table:hover::-webkit-scrollbar{\n    display: block;\n}\n.pos-sidebar .first-table::-webkit-scrollbar-track {\n  background: #f1f1f1;\n}\n\n.pos-sidebar .first-table::-webkit-scrollbar-thumb {\n  background: #888;\n  border-radius: 30px;\n}\n.pos-sidebar table thead tr {\n    position: sticky;\n    top: 0;\n}\n/* Pos Main Part End */\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./resources/js/assets/css/pos_responsive.css":
/*!******************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./resources/js/assets/css/pos_responsive.css ***!
  \******************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "@media(max-width: 1199px){\n\t.toggle-btn{\n\t\tdisplay: block;\n\t}\n\t.desktop-only{\n\t\tdisplay: none;\n\t}\n\t.mobile-only {\n\t\tdisplay: block;\n\t}\n\t.hr-mobile {\n\t\tdisplay: flex;\n\t\talign-items: center;\n\t}\n\t.collapse:not(.show){\n\t\tdisplay: block;\n\t}\n\t.accounts.mobile-only i {\n\t\tfont-size: 25px;\n\t\tcolor: #5c5c5c;\n\t}\n\t.dropdown-toggle::after{\n\t\tdisplay: none;\n\t}\n\t.time.mobile-only {\n\t\tpadding: 15px 20px;\n\t}\n\t.main-btns{\n\t\tmargin-right: 0;\n\t}\n\t.header-btns{\n\t\tpadding: 0 20px 20px 20px;\n\t\tdisplay: block;\n\t}\n\t.main-btns{\n\t\tdisplay: block;\n\t}\n\t.main-btns a{\n\t\twidth: 100%;\n\t\ttext-align: center;\n\t}\n\t.main-btns a +a{\n\t\tmargin-left: 0;\n\t\tmargin-top: 10px;\n\t}\n\t.time span{\n\t\tfont-size: 13px;\n\t}\n\t.pos-sidebar{\n\t\tmargin-bottom: 15px;\n\t}\n\t.hr-mobile {\n\t\tdisplay: flex;\n\t}\n}\n\n@media(max-width: 991px) {\n\n}\n\n@media(max-width: 767px) {\n\t.pos-sidebar table td{\n\t\twhite-space: nowrap;\n\t}\n\t.pos-sidebar table th{\n\t\twhite-space: nowrap;\n\t}\n}\n\n@media(max-width: 575px) {\n\t.logo img{\n\t\tmax-width: 180px;\n\t}\n\t.pos-main .padding{\n\t\twidth: 50%;\n\t}\n\t.pos-main .nav-item{\n\t\twidth: 100%;\n\t}\n\t.pos-sidebar .col-xs-10{\n\t\twidth: 83.33333333%;\n\t}\n\t.pos-sidebar .col-xs-2{\n\t\twidth: 16.66666667%;\n\t}\n\t.input-btn {\n\t\ttext-align: right;\n\t}\n\t.pos-main .nav-tabs .nav-link{\n\t\tpadding: 3px 10px;\n\t}\n}\n\n\n\n\n\n\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[3]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/PosProductLoadingSkeleton.vue?vue&type=style&index=0&id=b4de3684&lang=scss&scoped=true&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[3]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/PosProductLoadingSkeleton.vue?vue&type=style&index=0&id=b4de3684&lang=scss&scoped=true& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_12_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_12_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_12_0_rules_0_use_3_node_modules_vue_loader_lib_index_js_vue_loader_options_PosProductLoadingSkeleton_vue_vue_type_style_index_0_id_b4de3684_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[3]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PosProductLoadingSkeleton.vue?vue&type=style&index=0&id=b4de3684&lang=scss&scoped=true& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[3]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/PosProductLoadingSkeleton.vue?vue&type=style&index=0&id=b4de3684&lang=scss&scoped=true&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_12_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_12_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_12_0_rules_0_use_3_node_modules_vue_loader_lib_index_js_vue_loader_options_PosProductLoadingSkeleton_vue_vue_type_style_index_0_id_b4de3684_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_clonedRuleSet_12_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_12_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_12_0_rules_0_use_3_node_modules_vue_loader_lib_index_js_vue_loader_options_PosProductLoadingSkeleton_vue_vue_type_style_index_0_id_b4de3684_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Pos.vue?vue&type=style&index=0&lang=css&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Pos.vue?vue&type=style&index=0&lang=css& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Pos_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Pos.vue?vue&type=style&index=0&lang=css& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Pos.vue?vue&type=style&index=0&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Pos_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Pos_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/LoadingButton.vue?vue&type=style&index=0&id=2d71081e&scoped=true&lang=css&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/LoadingButton.vue?vue&type=style&index=0&id=2d71081e&scoped=true&lang=css& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_style_index_0_id_2d71081e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LoadingButton.vue?vue&type=style&index=0&id=2d71081e&scoped=true&lang=css& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/LoadingButton.vue?vue&type=style&index=0&id=2d71081e&scoped=true&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_style_index_0_id_2d71081e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_style_index_0_id_2d71081e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/OverlayLoading.vue?vue&type=style&index=0&id=3723dd28&scoped=true&lang=css&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/OverlayLoading.vue?vue&type=style&index=0&id=3723dd28&scoped=true&lang=css& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_OverlayLoading_vue_vue_type_style_index_0_id_3723dd28_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./OverlayLoading.vue?vue&type=style&index=0&id=3723dd28&scoped=true&lang=css& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/OverlayLoading.vue?vue&type=style&index=0&id=3723dd28&scoped=true&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_OverlayLoading_vue_vue_type_style_index_0_id_3723dd28_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_OverlayLoading_vue_vue_type_style_index_0_id_3723dd28_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/Layouts/pos/Header.vue":
/*!*********************************************!*\
  !*** ./resources/js/Layouts/pos/Header.vue ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Header_vue_vue_type_template_id_ff4cb3a8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Header.vue?vue&type=template&id=ff4cb3a8& */ "./resources/js/Layouts/pos/Header.vue?vue&type=template&id=ff4cb3a8&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");

var script = {}


/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  script,
  _Header_vue_vue_type_template_id_ff4cb3a8___WEBPACK_IMPORTED_MODULE_0__.render,
  _Header_vue_vue_type_template_id_ff4cb3a8___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Layouts/pos/Header.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Pos.vue":
/*!************************************!*\
  !*** ./resources/js/Pages/Pos.vue ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Pos_vue_vue_type_template_id_26bc003b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Pos.vue?vue&type=template&id=26bc003b& */ "./resources/js/Pages/Pos.vue?vue&type=template&id=26bc003b&");
/* harmony import */ var _Pos_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Pos.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Pos.vue?vue&type=script&lang=js&");
/* harmony import */ var _Pos_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Pos.vue?vue&type=style&index=0&lang=css& */ "./resources/js/Pages/Pos.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Pos_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Pos_vue_vue_type_template_id_26bc003b___WEBPACK_IMPORTED_MODULE_0__.render,
  _Pos_vue_vue_type_template_id_26bc003b___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Pos.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/Ui/LoadingButton.vue":
/*!******************************************************!*\
  !*** ./resources/js/components/Ui/LoadingButton.vue ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _LoadingButton_vue_vue_type_template_id_2d71081e_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LoadingButton.vue?vue&type=template&id=2d71081e&scoped=true& */ "./resources/js/components/Ui/LoadingButton.vue?vue&type=template&id=2d71081e&scoped=true&");
/* harmony import */ var _LoadingButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LoadingButton.vue?vue&type=script&lang=js& */ "./resources/js/components/Ui/LoadingButton.vue?vue&type=script&lang=js&");
/* harmony import */ var _LoadingButton_vue_vue_type_style_index_0_id_2d71081e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./LoadingButton.vue?vue&type=style&index=0&id=2d71081e&scoped=true&lang=css& */ "./resources/js/components/Ui/LoadingButton.vue?vue&type=style&index=0&id=2d71081e&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _LoadingButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _LoadingButton_vue_vue_type_template_id_2d71081e_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _LoadingButton_vue_vue_type_template_id_2d71081e_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "2d71081e",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/Ui/LoadingButton.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/Ui/OverlayLoading.vue":
/*!*******************************************************!*\
  !*** ./resources/js/components/Ui/OverlayLoading.vue ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _OverlayLoading_vue_vue_type_template_id_3723dd28_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./OverlayLoading.vue?vue&type=template&id=3723dd28&scoped=true& */ "./resources/js/components/Ui/OverlayLoading.vue?vue&type=template&id=3723dd28&scoped=true&");
/* harmony import */ var _OverlayLoading_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./OverlayLoading.vue?vue&type=script&lang=js& */ "./resources/js/components/Ui/OverlayLoading.vue?vue&type=script&lang=js&");
/* harmony import */ var _OverlayLoading_vue_vue_type_style_index_0_id_3723dd28_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./OverlayLoading.vue?vue&type=style&index=0&id=3723dd28&scoped=true&lang=css& */ "./resources/js/components/Ui/OverlayLoading.vue?vue&type=style&index=0&id=3723dd28&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _OverlayLoading_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _OverlayLoading_vue_vue_type_template_id_3723dd28_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _OverlayLoading_vue_vue_type_template_id_3723dd28_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "3723dd28",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/Ui/OverlayLoading.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/Ui/PosProductLoadingSkeleton.vue":
/*!******************************************************************!*\
  !*** ./resources/js/components/Ui/PosProductLoadingSkeleton.vue ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _PosProductLoadingSkeleton_vue_vue_type_template_id_b4de3684_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./PosProductLoadingSkeleton.vue?vue&type=template&id=b4de3684&scoped=true& */ "./resources/js/components/Ui/PosProductLoadingSkeleton.vue?vue&type=template&id=b4de3684&scoped=true&");
/* harmony import */ var _PosProductLoadingSkeleton_vue_vue_type_style_index_0_id_b4de3684_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./PosProductLoadingSkeleton.vue?vue&type=style&index=0&id=b4de3684&lang=scss&scoped=true& */ "./resources/js/components/Ui/PosProductLoadingSkeleton.vue?vue&type=style&index=0&id=b4de3684&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");

var script = {}
;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  script,
  _PosProductLoadingSkeleton_vue_vue_type_template_id_b4de3684_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _PosProductLoadingSkeleton_vue_vue_type_template_id_b4de3684_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "b4de3684",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/Ui/PosProductLoadingSkeleton.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/inputs/KeyboardNumberPicker.vue":
/*!*****************************************************************!*\
  !*** ./resources/js/components/inputs/KeyboardNumberPicker.vue ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _KeyboardNumberPicker_vue_vue_type_template_id_73ce4249___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./KeyboardNumberPicker.vue?vue&type=template&id=73ce4249& */ "./resources/js/components/inputs/KeyboardNumberPicker.vue?vue&type=template&id=73ce4249&");
/* harmony import */ var _KeyboardNumberPicker_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./KeyboardNumberPicker.vue?vue&type=script&lang=js& */ "./resources/js/components/inputs/KeyboardNumberPicker.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _KeyboardNumberPicker_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _KeyboardNumberPicker_vue_vue_type_template_id_73ce4249___WEBPACK_IMPORTED_MODULE_0__.render,
  _KeyboardNumberPicker_vue_vue_type_template_id_73ce4249___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/inputs/KeyboardNumberPicker.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Pos.vue?vue&type=script&lang=js&":
/*!*************************************************************!*\
  !*** ./resources/js/Pages/Pos.vue?vue&type=script&lang=js& ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Pos_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Pos.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Pos.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Pos_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/Ui/LoadingButton.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/js/components/Ui/LoadingButton.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LoadingButton.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/LoadingButton.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/Ui/OverlayLoading.vue?vue&type=script&lang=js&":
/*!********************************************************************************!*\
  !*** ./resources/js/components/Ui/OverlayLoading.vue?vue&type=script&lang=js& ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OverlayLoading_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./OverlayLoading.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/OverlayLoading.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OverlayLoading_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/inputs/KeyboardNumberPicker.vue?vue&type=script&lang=js&":
/*!******************************************************************************************!*\
  !*** ./resources/js/components/inputs/KeyboardNumberPicker.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_KeyboardNumberPicker_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./KeyboardNumberPicker.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/inputs/KeyboardNumberPicker.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_KeyboardNumberPicker_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/Ui/PosProductLoadingSkeleton.vue?vue&type=style&index=0&id=b4de3684&lang=scss&scoped=true&":
/*!****************************************************************************************************************************!*\
  !*** ./resources/js/components/Ui/PosProductLoadingSkeleton.vue?vue&type=style&index=0&id=b4de3684&lang=scss&scoped=true& ***!
  \****************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_12_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_12_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_12_0_rules_0_use_3_node_modules_vue_loader_lib_index_js_vue_loader_options_PosProductLoadingSkeleton_vue_vue_type_style_index_0_id_b4de3684_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader/dist/cjs.js!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[3]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PosProductLoadingSkeleton.vue?vue&type=style&index=0&id=b4de3684&lang=scss&scoped=true& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-12[0].rules[0].use[3]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/PosProductLoadingSkeleton.vue?vue&type=style&index=0&id=b4de3684&lang=scss&scoped=true&");


/***/ }),

/***/ "./resources/js/Pages/Pos.vue?vue&type=style&index=0&lang=css&":
/*!*********************************************************************!*\
  !*** ./resources/js/Pages/Pos.vue?vue&type=style&index=0&lang=css& ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Pos_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader/dist/cjs.js!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Pos.vue?vue&type=style&index=0&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Pos.vue?vue&type=style&index=0&lang=css&");


/***/ }),

/***/ "./resources/js/components/Ui/LoadingButton.vue?vue&type=style&index=0&id=2d71081e&scoped=true&lang=css&":
/*!***************************************************************************************************************!*\
  !*** ./resources/js/components/Ui/LoadingButton.vue?vue&type=style&index=0&id=2d71081e&scoped=true&lang=css& ***!
  \***************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_style_index_0_id_2d71081e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader/dist/cjs.js!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LoadingButton.vue?vue&type=style&index=0&id=2d71081e&scoped=true&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/LoadingButton.vue?vue&type=style&index=0&id=2d71081e&scoped=true&lang=css&");


/***/ }),

/***/ "./resources/js/components/Ui/OverlayLoading.vue?vue&type=style&index=0&id=3723dd28&scoped=true&lang=css&":
/*!****************************************************************************************************************!*\
  !*** ./resources/js/components/Ui/OverlayLoading.vue?vue&type=style&index=0&id=3723dd28&scoped=true&lang=css& ***!
  \****************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_OverlayLoading_vue_vue_type_style_index_0_id_3723dd28_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader/dist/cjs.js!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./OverlayLoading.vue?vue&type=style&index=0&id=3723dd28&scoped=true&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/OverlayLoading.vue?vue&type=style&index=0&id=3723dd28&scoped=true&lang=css&");


/***/ }),

/***/ "./resources/js/Layouts/pos/Header.vue?vue&type=template&id=ff4cb3a8&":
/*!****************************************************************************!*\
  !*** ./resources/js/Layouts/pos/Header.vue?vue&type=template&id=ff4cb3a8& ***!
  \****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Header_vue_vue_type_template_id_ff4cb3a8___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Header_vue_vue_type_template_id_ff4cb3a8___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Header_vue_vue_type_template_id_ff4cb3a8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Header.vue?vue&type=template&id=ff4cb3a8& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/pos/Header.vue?vue&type=template&id=ff4cb3a8&");


/***/ }),

/***/ "./resources/js/Pages/Pos.vue?vue&type=template&id=26bc003b&":
/*!*******************************************************************!*\
  !*** ./resources/js/Pages/Pos.vue?vue&type=template&id=26bc003b& ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Pos_vue_vue_type_template_id_26bc003b___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Pos_vue_vue_type_template_id_26bc003b___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Pos_vue_vue_type_template_id_26bc003b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Pos.vue?vue&type=template&id=26bc003b& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Pos.vue?vue&type=template&id=26bc003b&");


/***/ }),

/***/ "./resources/js/components/Ui/LoadingButton.vue?vue&type=template&id=2d71081e&scoped=true&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/Ui/LoadingButton.vue?vue&type=template&id=2d71081e&scoped=true& ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_template_id_2d71081e_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_template_id_2d71081e_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_template_id_2d71081e_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LoadingButton.vue?vue&type=template&id=2d71081e&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/LoadingButton.vue?vue&type=template&id=2d71081e&scoped=true&");


/***/ }),

/***/ "./resources/js/components/Ui/OverlayLoading.vue?vue&type=template&id=3723dd28&scoped=true&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/components/Ui/OverlayLoading.vue?vue&type=template&id=3723dd28&scoped=true& ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_OverlayLoading_vue_vue_type_template_id_3723dd28_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_OverlayLoading_vue_vue_type_template_id_3723dd28_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_OverlayLoading_vue_vue_type_template_id_3723dd28_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./OverlayLoading.vue?vue&type=template&id=3723dd28&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/OverlayLoading.vue?vue&type=template&id=3723dd28&scoped=true&");


/***/ }),

/***/ "./resources/js/components/Ui/PosProductLoadingSkeleton.vue?vue&type=template&id=b4de3684&scoped=true&":
/*!*************************************************************************************************************!*\
  !*** ./resources/js/components/Ui/PosProductLoadingSkeleton.vue?vue&type=template&id=b4de3684&scoped=true& ***!
  \*************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PosProductLoadingSkeleton_vue_vue_type_template_id_b4de3684_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PosProductLoadingSkeleton_vue_vue_type_template_id_b4de3684_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PosProductLoadingSkeleton_vue_vue_type_template_id_b4de3684_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PosProductLoadingSkeleton.vue?vue&type=template&id=b4de3684&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/PosProductLoadingSkeleton.vue?vue&type=template&id=b4de3684&scoped=true&");


/***/ }),

/***/ "./resources/js/components/inputs/KeyboardNumberPicker.vue?vue&type=template&id=73ce4249&":
/*!************************************************************************************************!*\
  !*** ./resources/js/components/inputs/KeyboardNumberPicker.vue?vue&type=template&id=73ce4249& ***!
  \************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_KeyboardNumberPicker_vue_vue_type_template_id_73ce4249___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_KeyboardNumberPicker_vue_vue_type_template_id_73ce4249___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_KeyboardNumberPicker_vue_vue_type_template_id_73ce4249___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./KeyboardNumberPicker.vue?vue&type=template&id=73ce4249& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/inputs/KeyboardNumberPicker.vue?vue&type=template&id=73ce4249&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/pos/Header.vue?vue&type=template&id=ff4cb3a8&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/pos/Header.vue?vue&type=template&id=ff4cb3a8& ***!
  \*******************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _vm._m(0)
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", [
      _c("header", { staticClass: "header", attrs: { id: "header" } }, [
        _c("div", { staticClass: "container-fluid" }, [
          _c("div", { staticClass: "h-wrap" }, [
            _c("div", { staticClass: "logo" }, [
              _c("a", { attrs: { href: "#" } }, [
                _c("img", { attrs: { src: "/img/logo1.png", alt: "images" } }),
              ]),
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "time desktop-only" }, [
              _c("span", [_vm._v("Friday, 19 November 2021, 04:15:26 PM")]),
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "header-right desktop-only" }, [
              _c("div", { staticClass: "navigation" }, [
                _c(
                  "nav",
                  { staticClass: "navbar navbar-expand-md navbar-light" },
                  [
                    _c(
                      "div",
                      {
                        staticClass: "collapse navbar-collapse",
                        attrs: { id: "navbarNavDropdown" },
                      },
                      [
                        _c("ul", { staticClass: "navbar-nav" }, [
                          _c("li", { staticClass: "nav-item active" }, [
                            _c(
                              "a",
                              { staticClass: "nav-link", attrs: { href: "#" } },
                              [_vm._v("Home")]
                            ),
                          ]),
                          _vm._v(" "),
                          _c("li", { staticClass: "nav-item active" }, [
                            _c(
                              "a",
                              { staticClass: "nav-link", attrs: { href: "#" } },
                              [_vm._v("POS Settings")]
                            ),
                          ]),
                          _vm._v(" "),
                          _c("li", { staticClass: "nav-item dropdown" }, [
                            _c(
                              "a",
                              {
                                staticClass: "nav-link dropdown-toggle",
                                attrs: {
                                  href: "#",
                                  id: "navbarDropdownMenuLink",
                                  "data-toggle": "dropdown",
                                  "aria-haspopup": "true",
                                  "aria-expanded": "false",
                                },
                              },
                              [
                                _vm._v(
                                  "\n                                            Sales\n                                        "
                                ),
                              ]
                            ),
                            _vm._v(" "),
                            _c(
                              "ul",
                              {
                                staticClass: "dropdown-menu",
                                attrs: {
                                  "aria-labelledby": "navbarDropdownMenuLink",
                                },
                              },
                              [
                                _c("li", [
                                  _c(
                                    "a",
                                    {
                                      staticClass: "dropdown-item",
                                      attrs: { href: "#" },
                                    },
                                    [_vm._v("Sales")]
                                  ),
                                ]),
                                _vm._v(" "),
                                _c("li", [
                                  _c(
                                    "a",
                                    {
                                      staticClass: "dropdown-item",
                                      attrs: { href: "#" },
                                    },
                                    [_vm._v("Suspended Sales")]
                                  ),
                                ]),
                              ]
                            ),
                          ]),
                        ]),
                      ]
                    ),
                  ]
                ),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "header-btns" }, [
                _c("div", { staticClass: "main-btns" }, [
                  _c("a", { attrs: { href: "#" } }, [
                    _c("i", { staticClass: "las la-chart-line" }),
                    _vm._v(" Today's Sales"),
                  ]),
                  _vm._v(" "),
                  _c("a", { attrs: { href: "#" } }, [
                    _c("i", { staticClass: "las la-bell" }),
                    _vm._v(" 1056 Product Alerts"),
                  ]),
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "accounts desktop-only" }, [
                  _c(
                    "nav",
                    { staticClass: "navbar navbar-expand-md navbar-light" },
                    [
                      _c(
                        "div",
                        {
                          staticClass: "collapse navbar-collapse",
                          attrs: { id: "navbarNavDropdown" },
                        },
                        [
                          _c("ul", { staticClass: "navbar-nav" }, [
                            _c("li", { staticClass: "nav-item dropdown" }, [
                              _c(
                                "a",
                                {
                                  staticClass: "nav-link dropdown-toggle",
                                  attrs: {
                                    href: "#",
                                    id: "navbarDropdownMenuLink",
                                    "data-toggle": "dropdown",
                                    "aria-haspopup": "true",
                                    "aria-expanded": "false",
                                  },
                                },
                                [
                                  _vm._v(
                                    "\n                                                Hi, Nectar Digit\n                                            "
                                  ),
                                ]
                              ),
                              _vm._v(" "),
                              _c(
                                "ul",
                                {
                                  staticClass: "dropdown-menu",
                                  attrs: {
                                    "aria-labelledby": "navbarDropdownMenuLink",
                                  },
                                },
                                [
                                  _c("li", [
                                    _c(
                                      "a",
                                      {
                                        staticClass: "dropdown-item",
                                        attrs: { href: "#" },
                                      },
                                      [_vm._v("Change Passeord")]
                                    ),
                                  ]),
                                  _vm._v(" "),
                                  _c("li", [
                                    _c(
                                      "a",
                                      {
                                        staticClass: "dropdown-item",
                                        attrs: { href: "#" },
                                      },
                                      [_vm._v("Logout")]
                                    ),
                                  ]),
                                ]
                              ),
                            ]),
                          ]),
                        ]
                      ),
                    ]
                  ),
                ]),
              ]),
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "hr-mobile" }, [
              _c("div", { staticClass: "accounts mobile-only" }, [
                _c(
                  "nav",
                  { staticClass: "navbar navbar-expand-md navbar-light" },
                  [
                    _c(
                      "div",
                      {
                        staticClass: "collapse navbar-collapse",
                        attrs: { id: "navbarNavDropdown" },
                      },
                      [
                        _c("ul", { staticClass: "navbar-nav" }, [
                          _c("li", { staticClass: "nav-item dropdown" }, [
                            _c(
                              "a",
                              {
                                staticClass: "nav-link dropdown-toggle",
                                attrs: {
                                  href: "#",
                                  id: "navbarDropdownMenuLink",
                                  "data-toggle": "dropdown",
                                  "aria-haspopup": "true",
                                  "aria-expanded": "false",
                                },
                              },
                              [_c("i", { staticClass: "las la-user-check" })]
                            ),
                            _vm._v(" "),
                            _c(
                              "ul",
                              {
                                staticClass: "dropdown-menu",
                                attrs: {
                                  "aria-labelledby": "navbarDropdownMenuLink",
                                },
                              },
                              [
                                _c("li", [
                                  _c(
                                    "a",
                                    {
                                      staticClass: "dropdown-item",
                                      attrs: { href: "#" },
                                    },
                                    [_vm._v("Change Passeord")]
                                  ),
                                ]),
                                _vm._v(" "),
                                _c("li", [
                                  _c(
                                    "a",
                                    {
                                      staticClass: "dropdown-item",
                                      attrs: { href: "#" },
                                    },
                                    [_vm._v("Logout")]
                                  ),
                                ]),
                              ]
                            ),
                          ]),
                        ]),
                      ]
                    ),
                  ]
                ),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "toggle-btn" }, [
                _c("div", { staticClass: "toggle-wrap" }, [
                  _c("span"),
                  _vm._v(" "),
                  _c("span"),
                  _vm._v(" "),
                  _c("span"),
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "toggle-wrap" }, [
                  _c("span"),
                  _vm._v(" "),
                  _c("span"),
                  _vm._v(" "),
                  _c("span"),
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "toggle-wrap" }, [
                  _c("span"),
                  _vm._v(" "),
                  _c("span"),
                  _vm._v(" "),
                  _c("span"),
                ]),
              ]),
            ]),
          ]),
        ]),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "sidenav", attrs: { id: "mySidenav" } }, [
        _c("div", { staticClass: "mobile-logo" }, [
          _c("a", { attrs: { href: "index.html" } }, [
            _c("img", { attrs: { src: "/img/logo1.png", alt: "logo" } }),
          ]),
          _vm._v(" "),
          _c(
            "a",
            {
              staticClass: "closebtn",
              attrs: { href: "javascript:void(0)", id: "close-btn" },
            },
            [_vm._v("×")]
          ),
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "no-bdr1" }, [
          _c("div", { staticClass: "time mobile-only" }, [
            _c("span", [_vm._v("Friday, 19 November 2021, 04:15:26 PM")]),
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "header-right mobile-only" }, [
            _c("div", { staticClass: "header-btns" }, [
              _c("div", { staticClass: "main-btns" }, [
                _c("a", { attrs: { href: "#" } }, [
                  _c("i", { staticClass: "las la-chart-line" }),
                  _vm._v(" Today's Sales"),
                ]),
                _vm._v(" "),
                _c("a", { attrs: { href: "#" } }, [
                  _c("i", { staticClass: "las la-bell" }),
                  _vm._v(" 1056 Product Alerts"),
                ]),
              ]),
            ]),
          ]),
          _vm._v(" "),
          _c("ul", { attrs: { id: "menu1" } }, [
            _c("li", [_c("a", { attrs: { href: "#" } }, [_vm._v("Home")])]),
            _vm._v(" "),
            _c("li", [
              _c("a", { attrs: { href: "#" } }, [_vm._v("POS Setting")]),
            ]),
            _vm._v(" "),
            _c("li", [
              _c("a", { staticClass: "has-arrow", attrs: { href: "#" } }, [
                _vm._v("Sales"),
              ]),
              _vm._v(" "),
              _c("ul", [
                _c("li", [
                  _c("a", { attrs: { href: "#" } }, [_vm._v("Sales")]),
                ]),
                _vm._v(" "),
                _c("li", [
                  _c("a", { attrs: { href: "#" } }, [
                    _vm._v("Suspended Sales"),
                  ]),
                ]),
              ]),
            ]),
          ]),
        ]),
      ]),
    ])
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Pos.vue?vue&type=template&id=26bc003b&":
/*!**********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Pos.vue?vue&type=template&id=26bc003b& ***!
  \**********************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "wrapper" },
    [
      _c("OverlayLoading", { attrs: { loading: _vm.overlayLoading } }),
      _vm._v(" "),
      _c("Header"),
      _vm._v(" "),
      _c("section", { staticClass: "pos-main-section" }, [
        _c("div", { staticClass: "container-fluid" }, [
          _c("div", { staticClass: "row" }, [
            _c("div", { staticClass: "col-lg-4 col-md-12" }, [
              _c("div", { staticClass: "pos-sidebar" }, [
                _c("form", { attrs: { action: "", method: "get" } }, [
                  _c("div", { staticClass: "row margin" }, [
                    _c(
                      "div",
                      { staticClass: "col-md-10 col-sm-10 col-xs-10 padding" },
                      [
                        _c(
                          "div",
                          { staticClass: "form-group customer-search-box" },
                          [
                            !_vm.selected.customer
                              ? _c("input", {
                                  directives: [
                                    {
                                      name: "model",
                                      rawName: "v-model",
                                      value: _vm.field.search.customer_name,
                                      expression: "field.search.customer_name",
                                    },
                                  ],
                                  staticClass: "form-control",
                                  attrs: {
                                    type: "text",
                                    placeholder:
                                      "Customer - Type 2 char for suggestions",
                                  },
                                  domProps: {
                                    value: _vm.field.search.customer_name,
                                  },
                                  on: {
                                    input: function ($event) {
                                      if ($event.target.composing) {
                                        return
                                      }
                                      _vm.$set(
                                        _vm.field.search,
                                        "customer_name",
                                        $event.target.value
                                      )
                                    },
                                  },
                                })
                              : _c(
                                  "div",
                                  { staticClass: "selected-input-box" },
                                  [
                                    _c(
                                      "span",
                                      { staticClass: "badge badge-primary" },
                                      [
                                        _vm._v(
                                          "\n                                                " +
                                            _vm._s(_vm.selected.customer.name) +
                                            "\n                                                "
                                        ),
                                        _c(
                                          "span",
                                          {
                                            staticClass:
                                              "close-icon ml-2 text-white",
                                            on: {
                                              click: function ($event) {
                                                return _vm.removeSelectedCustomer()
                                              },
                                            },
                                          },
                                          [
                                            _c("i", {
                                              staticClass: "fa fa-times",
                                              attrs: { "aria-hidden": "true" },
                                            }),
                                          ]
                                        ),
                                      ]
                                    ),
                                  ]
                                ),
                            _vm._v(" "),
                            _vm.field.search.customer_name
                              ? _c("div", { staticClass: "card search-box" }, [
                                  _c("div", { staticClass: "card-body" }, [
                                    !_vm.customerLoading
                                      ? _c(
                                          "ul",
                                          {
                                            staticClass:
                                              "search-customer__list",
                                          },
                                          [
                                            _vm._l(
                                              _vm.customers,
                                              function (customer) {
                                                return _vm.customers &&
                                                  _vm.customers.length > 0
                                                  ? _c(
                                                      "li",
                                                      {
                                                        staticClass:
                                                          "search-customer__list-item",
                                                        on: {
                                                          click: function (
                                                            $event
                                                          ) {
                                                            return _vm.addSelectedCustomer(
                                                              customer
                                                            )
                                                          },
                                                        },
                                                      },
                                                      [
                                                        _vm._v(
                                                          "\n                                                        " +
                                                            _vm._s(
                                                              customer.name
                                                            ) +
                                                            "\n                                                    "
                                                        ),
                                                      ]
                                                    )
                                                  : _vm._e()
                                              }
                                            ),
                                            _vm._v(" "),
                                            _vm.customers &&
                                            _vm.customers.length <= 0
                                              ? _c("li", [
                                                  _c("span", [
                                                    _vm._v(
                                                      "No Customers Found!!!"
                                                    ),
                                                  ]),
                                                ])
                                              : _vm._e(),
                                          ],
                                          2
                                        )
                                      : _c("div", { staticClass: "loading" }, [
                                          _vm._m(0),
                                          _vm._v(
                                            "\n                                                    Loading...\n                                                "
                                          ),
                                        ]),
                                  ]),
                                ])
                              : _vm._e(),
                          ]
                        ),
                      ]
                    ),
                    _vm._v(" "),
                    _vm._m(1),
                    _vm._v(" "),
                    _vm._m(2),
                    _vm._v(" "),
                    _vm._m(3),
                  ]),
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "table-responsive first-table" }, [
                  _c(
                    "table",
                    { staticClass: "table-bordered", attrs: { width: "100%" } },
                    [
                      _vm._m(4),
                      _vm._v(" "),
                      _c(
                        "tbody",
                        [
                          _vm._l(
                            _vm.selected.products,
                            function (product, index) {
                              return _c("tr", { key: index }, [
                                _c("td", { staticStyle: { width: "35px" } }, [
                                  _c(
                                    "button",
                                    {
                                      staticClass: "trash",
                                      on: {
                                        click: function ($event) {
                                          return _vm.removeSelectedProductItem(
                                            index
                                          )
                                        },
                                      },
                                    },
                                    [
                                      _c("i", {
                                        staticClass: "las la-trash-alt",
                                      }),
                                    ]
                                  ),
                                ]),
                                _vm._v(" "),
                                _c("td", { staticStyle: { width: "180px" } }, [
                                  _c("a", { attrs: { href: "#" } }, [
                                    _vm._v(_vm._s(product.product_name)),
                                  ]),
                                ]),
                                _vm._v(" "),
                                _c("td", { staticStyle: { width: "80px" } }, [
                                  _c("input", {
                                    directives: [
                                      {
                                        name: "model",
                                        rawName: "v-model",
                                        value: product.total_quantity,
                                        expression: "product.total_quantity",
                                      },
                                    ],
                                    attrs: { type: "text" },
                                    domProps: { value: product.total_quantity },
                                    on: {
                                      input: function ($event) {
                                        if ($event.target.composing) {
                                          return
                                        }
                                        _vm.$set(
                                          product,
                                          "total_quantity",
                                          $event.target.value
                                        )
                                      },
                                    },
                                  }),
                                ]),
                                _vm._v(" "),
                                _c("td", { staticStyle: { width: "90px" } }, [
                                  _vm._v(_vm._s(product.product_price)),
                                ]),
                              ])
                            }
                          ),
                          _vm._v(" "),
                          _vm._m(5),
                        ],
                        2
                      ),
                    ]
                  ),
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "table-reponsive" }, [
                  _c(
                    "table",
                    { staticClass: "table-bordered", attrs: { width: "100%" } },
                    [
                      _c("tbody", [
                        _c("tr", [
                          _c(
                            "td",
                            {
                              staticStyle: { width: "35px" },
                              attrs: { colspan: "1" },
                            },
                            [_vm._v(" ")]
                          ),
                          _vm._v(" "),
                          _vm._m(6),
                          _vm._v(" "),
                          _c("td", { staticStyle: { width: "80px" } }, [
                            _c("b", [
                              _vm._v(_vm._s(_vm.selected.products.length)),
                            ]),
                          ]),
                          _vm._v(" "),
                          _c("td", { staticStyle: { width: "90px" } }, [
                            _c("b", [
                              _vm._v(_vm._s(_vm.totalSelectedProductPrice)),
                            ]),
                          ]),
                        ]),
                        _vm._v(" "),
                        _vm._m(7),
                        _vm._v(" "),
                        _vm._m(8),
                        _vm._v(" "),
                        _vm._m(9),
                        _vm._v(" "),
                        _vm._m(10),
                      ]),
                    ]
                  ),
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "butk-btns" }, [
                  _c(
                    "button",
                    {
                      staticClass: "btn btn-danger",
                      attrs: { type: "submit" },
                    },
                    [_vm._v("Cancel")]
                  ),
                  _vm._v(" "),
                  _c(
                    "button",
                    {
                      staticClass: "btn btn-success",
                      attrs: { type: "submit" },
                    },
                    [_vm._v("Print")]
                  ),
                  _vm._v(" "),
                  _c(
                    "button",
                    {
                      staticClass: "btn btn-warning",
                      attrs: { type: "submit" },
                      on: {
                        click: function ($event) {
                          return _vm.actionSuspendProductItem()
                        },
                      },
                    },
                    [_vm._v("Suspen")]
                  ),
                ]),
                _vm._v(" "),
                _vm._m(11),
              ]),
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "col-lg-8 col-md-12" }, [
              _c("div", { staticClass: "pos-main" }, [
                _vm.categories && _vm.categories.length > 0
                  ? _c(
                      "ul",
                      {
                        staticClass: "nav nav-tabs",
                        attrs: { id: "myTab", role: "tablist" },
                      },
                      _vm._l(_vm.categories, function (category, index) {
                        return _c(
                          "li",
                          {
                            staticClass: "nav-item",
                            attrs: { role: "presentation" },
                          },
                          [
                            _c(
                              "button",
                              {
                                staticClass: "nav-link",
                                class: {
                                  active: _vm.selected.category === category.id,
                                },
                                on: {
                                  click: function ($event) {
                                    _vm.selected.category = category.id
                                  },
                                },
                              },
                              [
                                _vm._v(
                                  "\n                                    " +
                                    _vm._s(category.category_name) +
                                    "\n                                "
                                ),
                              ]
                            ),
                          ]
                        )
                      }),
                      0
                    )
                  : _vm._e(),
                _vm._v(" "),
                !_vm.productLoading && _vm.products && _vm.products.length > 0
                  ? _c(
                      "div",
                      { staticClass: "row margin" },
                      _vm._l(_vm.products, function (product) {
                        return _c(
                          "div",
                          {
                            staticClass:
                              "col-lg-2 col-md-3 col-sm-6 col-xs-6 padding",
                          },
                          [
                            _c("div", { staticClass: "pos-product-wrap" }, [
                              _c(
                                "button",
                                {
                                  attrs: { type: "submit" },
                                  on: {
                                    click: function ($event) {
                                      return _vm.addSelectedProductItem(product)
                                    },
                                  },
                                },
                                [
                                  _vm._m(12, true),
                                  _vm._v(" "),
                                  _c(
                                    "div",
                                    { staticClass: "pos-product-content" },
                                    [
                                      _c("span", [
                                        _vm._v(_vm._s(product.product_name)),
                                      ]),
                                    ]
                                  ),
                                ]
                              ),
                            ]),
                          ]
                        )
                      }),
                      0
                    )
                  : _vm._e(),
                _vm._v(" "),
                _vm.productLoading
                  ? _c("div", { staticClass: "pos-loading" }, [
                      _c(
                        "div",
                        { staticClass: "row" },
                        _vm._l(30, function (index) {
                          return _c(
                            "div",
                            {
                              staticClass:
                                "col-lg-2 col-md-3 col-sm-6 col-xs-6 padding",
                            },
                            [_c("PosProductLoadingSkeleton")],
                            1
                          )
                        }),
                        0
                      ),
                    ])
                  : _vm._e(),
                _vm._v(" "),
                _c(
                  "nav",
                  { attrs: { "aria-label": "Page navigation example" } },
                  [
                    _c("ul", { staticClass: "pagination" }, [
                      _vm.links.previous
                        ? _c("li", { staticClass: "page-item" }, [
                            _c(
                              "a",
                              {
                                staticClass: "page-link",
                                attrs: { href: "javascript:void(0)" },
                                on: {
                                  click: function ($event) {
                                    return _vm.previousProductList()
                                  },
                                },
                              },
                              [
                                _c("i", {
                                  staticClass: "las la-long-arrow-alt-left",
                                }),
                              ]
                            ),
                          ])
                        : _vm._e(),
                      _vm._v(" "),
                      _vm.links.next
                        ? _c("li", { staticClass: "page-item" }, [
                            _c(
                              "a",
                              {
                                staticClass: "page-link",
                                attrs: { href: "javascript:void(0)" },
                                on: {
                                  click: function ($event) {
                                    return _vm.nextProductList()
                                  },
                                },
                              },
                              [
                                _c("i", {
                                  staticClass: "las la-long-arrow-alt-right",
                                }),
                              ]
                            ),
                          ])
                        : _vm._e(),
                    ]),
                  ]
                ),
              ]),
            ]),
          ]),
        ]),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "modal fade", attrs: { id: "customerModal" } }, [
        _c("div", { staticClass: "modal-dialog" }, [
          _c("div", { staticClass: "modal-content" }, [
            _vm._m(13),
            _vm._v(" "),
            _c("div", { staticClass: "modal-body" }, [
              _c("div", { staticClass: "modal-form" }, [
                _c(
                  "form",
                  {
                    attrs: {
                      action: "",
                      method: "get",
                      "accept-charset": "utf-8",
                    },
                  },
                  [
                    _c("div", { staticClass: "row" }, [
                      _c("div", { staticClass: "col-md-6" }, [
                        _c("div", { staticClass: "form-group" }, [
                          _c("label", { attrs: { for: "company-name" } }, [
                            _vm._v("Customer Type"),
                          ]),
                          _vm._v(" "),
                          _c(
                            "select",
                            {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.field.customer.client_type,
                                  expression: "field.customer.client_type",
                                },
                              ],
                              staticClass: "form-control",
                              on: {
                                change: function ($event) {
                                  var $$selectedVal = Array.prototype.filter
                                    .call($event.target.options, function (o) {
                                      return o.selected
                                    })
                                    .map(function (o) {
                                      var val =
                                        "_value" in o ? o._value : o.value
                                      return val
                                    })
                                  _vm.$set(
                                    _vm.field.customer,
                                    "client_type",
                                    $event.target.multiple
                                      ? $$selectedVal
                                      : $$selectedVal[0]
                                  )
                                },
                              },
                            },
                            [
                              _c(
                                "option",
                                {
                                  attrs: {
                                    value: "",
                                    selected: "",
                                    disabled: "",
                                  },
                                },
                                [_vm._v("Select Type ")]
                              ),
                              _vm._v(" "),
                              _vm._l(
                                _vm.customerTypes,
                                function (customerType) {
                                  return _c(
                                    "option",
                                    { domProps: { value: customerType } },
                                    [
                                      _vm._v(
                                        "\n                                            " +
                                          _vm._s(customerType) +
                                          "\n                                            "
                                      ),
                                    ]
                                  )
                                }
                              ),
                            ],
                            2
                          ),
                          _vm._v(" "),
                          _vm.field.customer.errors &&
                          _vm.field.customer.errors["client_type"]
                            ? _c("span", { staticClass: "invalid-error" }, [
                                _vm._v(
                                  "\n                                            " +
                                    _vm._s(
                                      _vm.field.customer.errors[
                                        "client_type"
                                      ][0]
                                    ) +
                                    "\n                                        "
                                ),
                              ])
                            : _vm._e(),
                        ]),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-6" }, [
                        _c("div", { staticClass: "form-group" }, [
                          _c("label", { attrs: { for: "f-name" } }, [
                            _vm._v("Full Name"),
                          ]),
                          _vm._v(" "),
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.field.customer.name,
                                expression: "field.customer.name",
                              },
                            ],
                            staticClass: "form-control",
                            attrs: {
                              type: "text",
                              name: "f-name",
                              placeholder: "Full Name",
                            },
                            domProps: { value: _vm.field.customer.name },
                            on: {
                              input: function ($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.$set(
                                  _vm.field.customer,
                                  "name",
                                  $event.target.value
                                )
                              },
                            },
                          }),
                          _vm._v(" "),
                          _vm.field.customer.errors &&
                          _vm.field.customer.errors["name"]
                            ? _c("span", { staticClass: "invalid-error" }, [
                                _vm._v(
                                  "\n                                            " +
                                    _vm._s(
                                      _vm.field.customer.errors["name"][0]
                                    ) +
                                    "\n                                        "
                                ),
                              ])
                            : _vm._e(),
                        ]),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-6" }, [
                        _c("div", { staticClass: "form-group" }, [
                          _c("label", { attrs: { for: "address" } }, [
                            _vm._v("Address"),
                          ]),
                          _vm._v(" "),
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.field.customer.local_address,
                                expression: "field.customer.local_address",
                              },
                            ],
                            staticClass: "form-control",
                            attrs: {
                              type: "text",
                              name: "address",
                              placeholder: "Address",
                            },
                            domProps: {
                              value: _vm.field.customer.local_address,
                            },
                            on: {
                              input: function ($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.$set(
                                  _vm.field.customer,
                                  "local_address",
                                  $event.target.value
                                )
                              },
                            },
                          }),
                          _vm._v(" "),
                          _vm.field.customer.errors &&
                          _vm.field.customer.errors["local_address"]
                            ? _c("span", { staticClass: "invalid-error" }, [
                                _vm._v(
                                  "\n                                            " +
                                    _vm._s(
                                      _vm.field.customer.errors[
                                        "local_address"
                                      ][0]
                                    ) +
                                    "\n                                        "
                                ),
                              ])
                            : _vm._e(),
                        ]),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-6" }, [
                        _c("div", { staticClass: "form-group" }, [
                          _c("label", { attrs: { for: "email" } }, [
                            _vm._v("Email"),
                          ]),
                          _vm._v(" "),
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.field.customer.email,
                                expression: "field.customer.email",
                              },
                            ],
                            staticClass: "form-control",
                            attrs: {
                              type: "email",
                              name: "email",
                              placeholder: "Email",
                            },
                            domProps: { value: _vm.field.customer.email },
                            on: {
                              input: function ($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.$set(
                                  _vm.field.customer,
                                  "email",
                                  $event.target.value
                                )
                              },
                            },
                          }),
                          _vm._v(" "),
                          _vm.field.customer.errors &&
                          _vm.field.customer.errors["email"]
                            ? _c("span", { staticClass: "invalid-error" }, [
                                _vm._v(
                                  "\n                                            " +
                                    _vm._s(
                                      _vm.field.customer.errors["email"][0]
                                    ) +
                                    "\n                                        "
                                ),
                              ])
                            : _vm._e(),
                        ]),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-6" }, [
                        _c("div", { staticClass: "form-group" }, [
                          _c("label", { attrs: { for: "phone" } }, [
                            _vm._v("Phone"),
                          ]),
                          _vm._v(" "),
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.field.customer.phone,
                                expression: "field.customer.phone",
                              },
                            ],
                            staticClass: "form-control",
                            attrs: { type: "text" },
                            domProps: { value: _vm.field.customer.phone },
                            on: {
                              input: function ($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.$set(
                                  _vm.field.customer,
                                  "phone",
                                  $event.target.value
                                )
                              },
                            },
                          }),
                          _vm._v(" "),
                          _vm.field.customer.errors &&
                          _vm.field.customer.errors["phone"]
                            ? _c("span", { staticClass: "invalid-error" }, [
                                _vm._v(
                                  "\n                                            " +
                                    _vm._s(
                                      _vm.field.customer.errors["phone"][0]
                                    ) +
                                    "\n                                        "
                                ),
                              ])
                            : _vm._e(),
                        ]),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-6" }, [
                        _c("div", { staticClass: "form-group" }, [
                          _c("label", { attrs: { for: "city" } }, [
                            _vm._v("Provience"),
                          ]),
                          _vm._v(" "),
                          _c(
                            "select",
                            {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.field.customer.province,
                                  expression: "field.customer.province",
                                },
                              ],
                              staticClass: "form-control",
                              on: {
                                change: function ($event) {
                                  var $$selectedVal = Array.prototype.filter
                                    .call($event.target.options, function (o) {
                                      return o.selected
                                    })
                                    .map(function (o) {
                                      var val =
                                        "_value" in o ? o._value : o.value
                                      return val
                                    })
                                  _vm.$set(
                                    _vm.field.customer,
                                    "province",
                                    $event.target.multiple
                                      ? $$selectedVal
                                      : $$selectedVal[0]
                                  )
                                },
                              },
                            },
                            [
                              _c(
                                "option",
                                {
                                  attrs: {
                                    value: "",
                                    selected: "",
                                    disabled: "",
                                  },
                                },
                                [_vm._v("--")]
                              ),
                              _vm._v(" "),
                              _vm._l(_vm.provinces, function (provience) {
                                return _c(
                                  "option",
                                  { domProps: { value: provience.id } },
                                  [_vm._v(_vm._s(provience.eng_name))]
                                )
                              }),
                            ],
                            2
                          ),
                          _vm._v(" "),
                          _vm.field.customer.errors &&
                          _vm.field.customer.errors["province"]
                            ? _c("span", { staticClass: "invalid-error" }, [
                                _vm._v(
                                  "\n                                            " +
                                    _vm._s(
                                      _vm.field.customer.errors["province"][0]
                                    ) +
                                    "\n                                        "
                                ),
                              ])
                            : _vm._e(),
                        ]),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-6" }, [
                        _c("div", { staticClass: "form-group" }, [
                          _c("label", { attrs: { for: "city" } }, [
                            _vm._v("District"),
                          ]),
                          _vm._v(" "),
                          _c(
                            "select",
                            {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.field.customer.district,
                                  expression: "field.customer.district",
                                },
                              ],
                              staticClass: "form-control",
                              on: {
                                change: function ($event) {
                                  var $$selectedVal = Array.prototype.filter
                                    .call($event.target.options, function (o) {
                                      return o.selected
                                    })
                                    .map(function (o) {
                                      var val =
                                        "_value" in o ? o._value : o.value
                                      return val
                                    })
                                  _vm.$set(
                                    _vm.field.customer,
                                    "district",
                                    $event.target.multiple
                                      ? $$selectedVal
                                      : $$selectedVal[0]
                                  )
                                },
                              },
                            },
                            [
                              _c(
                                "option",
                                {
                                  attrs: {
                                    value: "",
                                    selected: "",
                                    disabled: "",
                                  },
                                },
                                [_vm._v("--")]
                              ),
                              _vm._v(" "),
                              _vm._l(_vm.districts, function (district) {
                                return _c(
                                  "option",
                                  { domProps: { value: district.id } },
                                  [_vm._v(_vm._s(district.dist_name))]
                                )
                              }),
                            ],
                            2
                          ),
                          _vm._v(" "),
                          _vm.field.customer.errors &&
                          _vm.field.customer.errors["district"]
                            ? _c("span", { staticClass: "invalid-error" }, [
                                _vm._v(
                                  "\n                                            " +
                                    _vm._s(
                                      _vm.field.customer.errors["district"][0]
                                    ) +
                                    "\n                                        "
                                ),
                              ])
                            : _vm._e(),
                        ]),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-12" }, [
                        _c(
                          "div",
                          { staticClass: "form-group" },
                          [
                            _c("LoadingButton", {
                              staticClass: "btn btn-primary w-100",
                              attrs: {
                                type: "button",
                                loading: _vm.submitCutomerLoading,
                                title: "Add Customer",
                              },
                              on: {
                                load: function ($event) {
                                  return _vm.actionCreateCustomer()
                                },
                              },
                            }),
                          ],
                          1
                        ),
                      ]),
                    ]),
                  ]
                ),
              ]),
            ]),
          ]),
        ]),
      ]),
    ],
    1
  )
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      {
        staticClass: "spinner-border text-info mr-2",
        staticStyle: { width: "16px", height: "16px" },
        attrs: { role: "status" },
      },
      [_c("span", { staticClass: "sr-only" }, [_vm._v("Loading...")])]
    )
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "col-md-2 col-sm-2 col-xs-2 padding" }, [
      _c("div", { staticClass: "input-btn" }, [
        _c(
          "button",
          {
            staticClass: "btn btn-primary",
            attrs: {
              type: "button",
              "data-toggle": "modal",
              "data-target": "#customerModal",
            },
          },
          [_c("i", { staticClass: "las la-plus" })]
        ),
      ]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "col-md-6 padding" }, [
      _c("div", { staticClass: "form-group" }, [
        _c("input", {
          staticClass: "form-control",
          attrs: { type: "text", name: "code", placeholder: "Barcode Scanner" },
        }),
      ]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "col-md-6 padding" }, [
      _c("div", { staticClass: "form-group" }, [
        _c("input", {
          staticClass: "form-control",
          attrs: { type: "text", name: "code", placeholder: "Product Code" },
        }),
      ]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("th", [_c("i", { staticClass: "las la-trash-alt" })]),
        _vm._v(" "),
        _c("th", [_vm._v("Product")]),
        _vm._v(" "),
        _c("th", [_vm._v("Qty")]),
        _vm._v(" "),
        _c("th", [_vm._v("Price")]),
      ]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("tr", [
      _c("td", { staticStyle: { width: "35px" } }, [
        _c("button", { staticClass: "trash", attrs: { type: "submit" } }, [
          _c("i", { staticClass: "las la-trash-alt" }),
        ]),
      ]),
      _vm._v(" "),
      _c("td", { staticStyle: { width: "180px" } }, [
        _c("a", { attrs: { href: "#" } }, [_vm._v("Tourist Kids -07")]),
      ]),
      _vm._v(" "),
      _c("td", { staticStyle: { width: "80px" } }, [
        _c("input", {
          staticClass: "alignRight keyboard",
          attrs: { type: "text", value: "1" },
        }),
      ]),
      _vm._v(" "),
      _c("td", { staticStyle: { width: "90px" } }, [_vm._v("250.44")]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("td", { staticStyle: { width: "180px" } }, [
      _c("b", [_vm._v("Total:")]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("tr", [
      _c("td", { staticStyle: { width: "35px" }, attrs: { colspan: "1" } }),
      _vm._v(" "),
      _c("td", { staticStyle: { width: "180px" } }, [
        _c("b", [_vm._v("Vat:")]),
      ]),
      _vm._v(" "),
      _c("td", { staticStyle: { width: "80px" } }),
      _vm._v(" "),
      _c("td", { staticStyle: { width: "90px" } }, [_c("b", [_vm._v("0.00")])]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("tr", [
      _c("td", { staticStyle: { width: "35px" }, attrs: { colspan: "1" } }),
      _vm._v(" "),
      _c("td", { staticStyle: { width: "180px" } }, [
        _c("b", [_vm._v("Bulk Discount:")]),
      ]),
      _vm._v(" "),
      _c("td", { attrs: { colspan: "2" } }, [
        _c("input", { staticClass: "form-control" }),
      ]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("tr", [
      _c("td", { staticStyle: { width: "35px" }, attrs: { colspan: "1" } }),
      _vm._v(" "),
      _c("td", { staticStyle: { width: "180px" } }, [
        _c("b", [_vm._v("Discount:")]),
      ]),
      _vm._v(" "),
      _c("td", { staticStyle: { width: "80px" } }),
      _vm._v(" "),
      _c("td", { staticStyle: { width: "90px" } }, [_c("b", [_vm._v("0.00")])]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("tr", [
      _c("td", { staticStyle: { width: "35px" }, attrs: { colspan: "1" } }),
      _vm._v(" "),
      _c("td", { staticStyle: { width: "180px" } }, [
        _c("b", [_vm._v("Total Cost:")]),
      ]),
      _vm._v(" "),
      _c("td", { staticStyle: { width: "80px" } }),
      _vm._v(" "),
      _c("td", { staticStyle: { width: "90px" } }, [
        _c("b", [_vm._v("1050.32")]),
      ]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "pay-btn" }, [
      _c(
        "button",
        { staticClass: "btn btn-success", attrs: { type: "submit" } },
        [_vm._v("Payment")]
      ),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "pos-product-img" }, [
      _c("img", { attrs: { src: "/img/product.png", alt: "images" } }),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "modal-header" }, [
      _c(
        "h5",
        { staticClass: "modal-title", attrs: { id: "exampleModalLabel" } },
        [_vm._v("New Customer")]
      ),
      _vm._v(" "),
      _c("button", {
        staticClass: "btn-close",
        attrs: {
          type: "button",
          "data-bs-dismiss": "modal",
          "aria-label": "Close",
        },
      }),
    ])
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/LoadingButton.vue?vue&type=template&id=2d71081e&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/LoadingButton.vue?vue&type=template&id=2d71081e&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "button",
    {
      staticClass: "btn btn-primary btn_submit",
      class: { disabled: _vm.loading === true },
      style: _vm.style_,
      attrs: { type: _vm.type },
      on: {
        click: function ($event) {
          return _vm.submit()
        },
      },
    },
    [
      _vm.loading === true
        ? _c("span", { staticClass: "spinner-border spinner-border-sm mr-2" })
        : _vm._e(),
      _vm._v("\n    " + _vm._s(_vm.title) + "\n"),
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/OverlayLoading.vue?vue&type=template&id=3723dd28&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/OverlayLoading.vue?vue&type=template&id=3723dd28&scoped=true& ***!
  \*****************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _vm.loading
    ? _c("div", { staticClass: "overlay" }, [
        _c("div", { staticClass: "content bg-primary text-white" }, [
          _vm._m(0),
          _vm._v(" "),
          _c("span", {}, [_vm._v(_vm._s(_vm.text))]),
        ]),
      ])
    : _vm._e()
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      {
        staticClass: "spinner-border text-white mr-2",
        attrs: { role: "status" },
      },
      [_c("span", { staticClass: "sr-only" }, [_vm._v("Loading...")])]
    )
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/PosProductLoadingSkeleton.vue?vue&type=template&id=b4de3684&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Ui/PosProductLoadingSkeleton.vue?vue&type=template&id=b4de3684&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _vm._m(0)
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "card post-card p-3" }, [
      _c("div", { staticClass: "post-card__header mb-3" }),
      _vm._v(" "),
      _c("div", { staticClass: "post-card__footer" }, [
        _c("p", { staticClass: "post-card__title mb-0" }),
      ]),
    ])
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/inputs/KeyboardNumberPicker.vue?vue&type=template&id=73ce4249&":
/*!***************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/inputs/KeyboardNumberPicker.vue?vue&type=template&id=73ce4249& ***!
  \***************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("input", {
    staticClass: "alignRight keyboard",
    attrs: { type: "text", value: "1" },
    on: {
      change: function ($event) {
        return _vm.alert(_vm.$element)
      },
    },
  })
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ normalizeComponent)
/* harmony export */ });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () {
        injectStyles.call(
          this,
          (options.functional ? this.parent : this).$root.$options.shadowRoot
        )
      }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ })

}]);