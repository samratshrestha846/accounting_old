"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_Billing_PosBillingInvoice_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    billing: {
      type: Object,
      required: true
    },
    userCompany: {
      type: Object,
      required: true
    }
  },
  data: function data() {
    return {
      baseUrl: window.location.origin
    };
  },
  computed: {
    company: function company() {
      var _this$userCompany;

      return (_this$userCompany = this.userCompany) === null || _this$userCompany === void 0 ? void 0 : _this$userCompany.company;
    },
    customer: function customer() {
      return this.billing.client || null;
    },
    billingExtras: function billingExtras() {
      return this.billing.billingextras || [];
    },
    totalItems: function totalItems() {
      return this.billingExtras.reduce(function (a, b) {
        return +a + +b.quantity;
      }, 0);
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=style&index=0&id=1b46cace&scoped=true&lang=css&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=style&index=0&id=1b46cace&scoped=true&lang=css& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
___CSS_LOADER_EXPORT___.push([module.id, "\n.wrapper[data-v-1b46cace]{\r\n    width: 280px;\r\n    margin: 0 auto;\n}\n.text-right[data-v-1b46cace]{\r\n    text-align: right;\n}\n.text-left[data-v-1b46cace]{\r\n    text-align: left;\n}\np[data-v-1b46cace]{\r\n    font-size: 12px;\n}\ntable[data-v-1b46cace] {\r\n    width: 100%;\n}\n.brand-logo[data-v-1b46cace]{\r\n    max-width: 250px;\r\n    width: auto;\n}\n.company-name[data-v-1b46cace]{\r\n    text-transform:uppercase;\r\n    font-size: 0.9rem;\r\n    font-weight: bold;\r\n    text-align: center;\n}\n.col[data-v-1b46cace]{\r\n    width: 100%;\r\n    display: flex;\r\n    padding-left: 0;\r\n    padding-right: 0;\r\n    margin-bottom: 4px;\n}\n.col .col-6[data-v-1b46cace]{\r\n    flex-grow: 1;\r\n    padding-left: 0;\n}\n.col .col-12[data-v-1b46cace]{\r\n    padding-left: 0;\n}\n.product-table[data-v-1b46cace]{\r\n    border-bottom: 1px solid #111;\n}\n.product-table th[data-v-1b46cace]{\r\n    padding-top: 2px;\r\n    padding-bottom: 2px;\r\n    border: none;\r\n    border-bottom: 1px solid #111;\n}\n.btn[data-v-1b46cace] {\r\n    width:100%;\r\n    cursor:pointer;\r\n    font-size:12px;\r\n    text-align: center;\r\n    border:1px solid #FFA93C;\r\n    padding: 10px 1px;\r\n    font-weight:bold;\r\n    border-radius: 0;\n}\n.btn-success[data-v-1b46cace] {\r\n    color: rgb(0, 0, 0);\r\n    background-color: rgb(79, 169, 80);\r\n    border: 2px solid rgb(79, 169, 80);\n}\n.btn-warning[data-v-1b46cace] {\r\n    background-color: rgb(255, 169, 60);\r\n    color: rgb(0, 0, 0);\r\n    border: 1px solid rgb(255, 169, 60);\n}\n.btn-primary[data-v-1b46cace]{\r\n    color: rgb(255, 255, 255);\r\n    background-color: rgb(0, 127, 255);\r\n    border: 2px solid rgb(0, 127, 255);\n}\r\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=style&index=0&id=1b46cace&scoped=true&lang=css&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=style&index=0&id=1b46cace&scoped=true&lang=css& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_PosBillingInvoice_vue_vue_type_style_index_0_id_1b46cace_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PosBillingInvoice.vue?vue&type=style&index=0&id=1b46cace&scoped=true&lang=css& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=style&index=0&id=1b46cace&scoped=true&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_PosBillingInvoice_vue_vue_type_style_index_0_id_1b46cace_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_PosBillingInvoice_vue_vue_type_style_index_0_id_1b46cace_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/Pages/Billing/PosBillingInvoice.vue":
/*!**********************************************************!*\
  !*** ./resources/js/Pages/Billing/PosBillingInvoice.vue ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _PosBillingInvoice_vue_vue_type_template_id_1b46cace_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./PosBillingInvoice.vue?vue&type=template&id=1b46cace&scoped=true& */ "./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=template&id=1b46cace&scoped=true&");
/* harmony import */ var _PosBillingInvoice_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./PosBillingInvoice.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=script&lang=js&");
/* harmony import */ var _PosBillingInvoice_vue_vue_type_style_index_0_id_1b46cace_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./PosBillingInvoice.vue?vue&type=style&index=0&id=1b46cace&scoped=true&lang=css& */ "./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=style&index=0&id=1b46cace&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _PosBillingInvoice_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _PosBillingInvoice_vue_vue_type_template_id_1b46cace_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _PosBillingInvoice_vue_vue_type_template_id_1b46cace_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "1b46cace",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Billing/PosBillingInvoice.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=script&lang=js&":
/*!***********************************************************************************!*\
  !*** ./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PosBillingInvoice_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PosBillingInvoice.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PosBillingInvoice_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=style&index=0&id=1b46cace&scoped=true&lang=css&":
/*!*******************************************************************************************************************!*\
  !*** ./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=style&index=0&id=1b46cace&scoped=true&lang=css& ***!
  \*******************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_PosBillingInvoice_vue_vue_type_style_index_0_id_1b46cace_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader/dist/cjs.js!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PosBillingInvoice.vue?vue&type=style&index=0&id=1b46cace&scoped=true&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=style&index=0&id=1b46cace&scoped=true&lang=css&");


/***/ }),

/***/ "./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=template&id=1b46cace&scoped=true&":
/*!*****************************************************************************************************!*\
  !*** ./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=template&id=1b46cace&scoped=true& ***!
  \*****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PosBillingInvoice_vue_vue_type_template_id_1b46cace_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PosBillingInvoice_vue_vue_type_template_id_1b46cace_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PosBillingInvoice_vue_vue_type_template_id_1b46cace_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PosBillingInvoice.vue?vue&type=template&id=1b46cace&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=template&id=1b46cace&scoped=true&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=template&id=1b46cace&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Billing/PosBillingInvoice.vue?vue&type=template&id=1b46cace&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "wrapper" }, [
    _c("img", {
      staticClass: "brand-logo",
      attrs: {
        src: _vm.baseUrl + "/uploads/" + _vm.company.company_logo,
        alt: "Biller logo",
      },
    }),
    _vm._v(" "),
    _c("h3", { staticClass: "company-name" }, [
      _vm._v(_vm._s(_vm.company.name)),
    ]),
    _vm._v(" "),
    _c("p", { staticClass: "company-address" }, [
      _vm._v(_vm._s(_vm.company.local_address)),
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "col" }, [
      _c("div", { staticClass: "col-6 text-left" }, [
        _c("span", [_vm._v("TAX/PAN Number: " + _vm._s(_vm.company.pan_vat))]),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "col-6 text-right" }, [
        _c("span", [_vm._v("Tel: " + _vm._s(_vm.company.phone))]),
      ]),
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "col" }, [
      _c("div", { staticClass: "col-12 text-left" }, [
        _c("span", [
          _vm._v("Reference No: " + _vm._s(_vm.billing.reference_no)),
        ]),
      ]),
    ]),
    _vm._v(" "),
    _vm._m(0),
    _vm._v(" "),
    _c("div", { staticClass: "col" }, [
      _c("div", { staticClass: "col-6 text-left" }, [
        _c("span", [_vm._v("Customer: " + _vm._s(_vm.customer.name))]),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "col-6 text-right" }, [
        _c("span", [_vm._v("Date: " + _vm._s(_vm.billing.eng_date))]),
      ]),
    ]),
    _vm._v(" "),
    _c("div", {}, [
      _c(
        "table",
        { staticClass: "table", attrs: { cellspacing: "0", border: "0" } },
        [
          _vm._m(1),
          _vm._v(" "),
          _c(
            "tbody",
            _vm._l(_vm.billingExtras, function (billingExtra, index) {
              return _c("tr", [
                _c(
                  "td",
                  { staticStyle: { "text-align": "center", width: "30px" } },
                  [_vm._v(_vm._s(index + 1))]
                ),
                _vm._v(" "),
                _c(
                  "td",
                  { staticStyle: { "text-align": "left", width: "180px" } },
                  [_vm._v("7005 Tourist")]
                ),
                _vm._v(" "),
                _c(
                  "td",
                  { staticStyle: { "text-align": "center", width: "50px" } },
                  [_vm._v(_vm._s(billingExtra.quantity))]
                ),
                _vm._v(" "),
                _c(
                  "td",
                  { staticStyle: { "text-align": "right", width: "55px" } },
                  [_vm._v(_vm._s(billingExtra.rate))]
                ),
                _vm._v(" "),
                _c(
                  "td",
                  { staticStyle: { "text-align": "right", width: "65px" } },
                  [_vm._v(_vm._s(billingExtra.total))]
                ),
              ])
            }),
            0
          ),
        ]
      ),
      _vm._v(" "),
      _c(
        "table",
        { staticClass: "totals", attrs: { cellspacing: "0", border: "0" } },
        [
          _c("tbody", [
            _c("tr", [
              _c("td", { staticStyle: { "text-align": "left" } }, [
                _vm._v("Total Items"),
              ]),
              _vm._v(" "),
              _c(
                "td",
                {
                  staticStyle: {
                    "text-align": "right",
                    "padding-right": "1.5%",
                    "border-right": "1px solid #999",
                    "font-weight": "bold",
                  },
                },
                [_vm._v(_vm._s(_vm.totalItems))]
              ),
              _vm._v(" "),
              _c(
                "td",
                {
                  staticStyle: { "text-align": "left", "padding-left": "1.5%" },
                },
                [_vm._v("Total")]
              ),
              _vm._v(" "),
              _c(
                "td",
                {
                  staticStyle: { "text-align": "right", "font-weight": "bold" },
                },
                [_vm._v(_vm._s(_vm.billing.subtotal))]
              ),
            ]),
            _vm._v(" "),
            _vm._m(2),
            _vm._v(" "),
            _c("tr", [
              _c(
                "td",
                {
                  staticStyle: { "text-align": "left" },
                  attrs: { colspan: "2" },
                },
                [_vm._v("Discount")]
              ),
              _vm._v(" "),
              _c(
                "td",
                {
                  staticStyle: { "text-align": "right", "font-weight": "bold" },
                  attrs: { colspan: "2" },
                },
                [_vm._v(_vm._s(_vm.billing.discountamount))]
              ),
            ]),
            _vm._v(" "),
            _c("tr", [
              _c(
                "td",
                {
                  staticStyle: { "text-align": "left" },
                  attrs: { colspan: "2" },
                },
                [_vm._v("Tax")]
              ),
              _vm._v(" "),
              _c(
                "td",
                {
                  staticStyle: { "text-align": "right", "font-weight": "bold" },
                  attrs: { colspan: "2" },
                },
                [_vm._v(_vm._s(_vm.billing.taxamount))]
              ),
            ]),
            _vm._v(" "),
            _c("tr", [
              _c(
                "td",
                {
                  staticStyle: {
                    "text-align": "left",
                    "font-weight": "bold",
                    "border-top": "1px solid #000",
                    "padding-top": "10px",
                  },
                  attrs: { colspan: "2" },
                },
                [_vm._v("Total Payable")]
              ),
              _vm._v(" "),
              _c(
                "td",
                {
                  staticStyle: {
                    "border-top": "1px solid #000",
                    "padding-top": "10px",
                    "text-align": "right",
                    "font-weight": "bold",
                  },
                  attrs: { colspan: "2" },
                },
                [_vm._v(_vm._s(_vm.billing.grandtotal))]
              ),
            ]),
            _vm._v(" "),
            _vm._m(3),
            _vm._v(" "),
            _vm._m(4),
          ]),
        ]
      ),
    ]),
    _vm._v(" "),
    _vm._m(5),
  ])
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "col" }, [
      _c("div", { staticClass: "col-12 text-left" }, [
        _c("span", [_vm._v("Pos Header Name: Nectargit")]),
      ]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", { staticClass: "product-table" }, [
        _c("th", [_c("em", [_vm._v("#")])]),
        _vm._v(" "),
        _c("th", [_vm._v("Description")]),
        _vm._v(" "),
        _c("th", [_vm._v("Qty")]),
        _vm._v(" "),
        _c("th", [_vm._v("Price")]),
        _vm._v(" "),
        _c("th", [_vm._v("Total")]),
      ]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("tr", [_c("td"), _vm._v(" "), _c("td")])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("tr", [
      _c(
        "td",
        {
          staticStyle: {
            "text-align": "left",
            "font-weight": "bold",
            "padding-top": "5px",
          },
          attrs: { colspan: "2" },
        },
        [_vm._v("Paid")]
      ),
      _vm._v(" "),
      _c(
        "td",
        {
          staticStyle: {
            "padding-top": "5px",
            "text-align": "right",
            "font-weight": "bold",
          },
          attrs: { colspan: "2" },
        },
        [_vm._v("500.00")]
      ),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("tr", [
      _c(
        "td",
        {
          staticStyle: {
            "text-align": "left",
            "font-weight": "bold",
            "padding-top": "5px",
          },
          attrs: { colspan: "2" },
        },
        [_vm._v("Change")]
      ),
      _vm._v(" "),
      _c(
        "td",
        {
          staticStyle: {
            "padding-top": "5px",
            "text-align": "right",
            "font-weight": "bold",
          },
          attrs: { colspan: "2" },
        },
        [_vm._v("30.00")]
      ),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      {
        staticStyle: { "padding-top": "10px", "text-transform": "uppercase" },
        attrs: { id: "buttons" },
      },
      [
        _c("div", { staticClass: "col" }, [
          _c(
            "span",
            {
              staticClass: "left",
              staticStyle: { width: "60%", "margin-right": "10px" },
            },
            [
              _c(
                "button",
                {
                  staticClass: "btn btn-success",
                  attrs: {
                    type: "button",
                    onclick: "window.print();return false;",
                  },
                },
                [_vm._v("Print")]
              ),
            ]
          ),
          _vm._v(" "),
          _c("span", { staticClass: "right", staticStyle: { width: "40%" } }, [
            _c(
              "button",
              {
                staticClass: "btn btn-warning",
                attrs: {
                  type: "button",
                  onclick: "window.print();return false;",
                },
              },
              [_vm._v("Print")]
            ),
          ]),
        ]),
        _vm._v(" "),
        _c("div", { staticStyle: { clear: "both" } }),
        _vm._v(" "),
        _c("a", { staticClass: "btn btn-primary", attrs: { href: "#" } }, [
          _vm._v("Back to POS"),
        ]),
        _vm._v(" "),
        _c("div", { staticStyle: { clear: "both" } }),
        _vm._v(" "),
        _c("div", { staticStyle: { background: "#F5F5F5", padding: "10px" } }, [
          _c("p", { staticStyle: { "font-weight": "bold" } }, [
            _vm._v(
              "Please don't forget to disble the header and footer in browser print settings."
            ),
          ]),
          _vm._v(" "),
          _c("p", { staticStyle: { "text-transform": "capitalize" } }, [
            _c("strong", [_vm._v("FF:")]),
            _vm._v(
              " File > Print Setup > Margin & Header/Footer Make all --blank--\r\n            "
            ),
          ]),
          _vm._v(" "),
          _c("p", { staticStyle: { "text-transform": "capitalize" } }, [
            _c("strong", [_vm._v("chrome:")]),
            _vm._v(
              " Menu > Print > Disable Header/Footer in Option & Set Margins to None\r\n            "
            ),
          ]),
        ]),
        _vm._v(" "),
        _c("div", { staticStyle: { clear: "both" } }),
      ]
    )
  },
]
render._withStripped = true



/***/ })

}]);