/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */,
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(2);
__webpack_require__(3);
module.exports = __webpack_require__(4);


/***/ }),
/* 2 */
/***/ (function(module, exports) {


new Vue({
	el: '#app',
	data: {
		inputNumbers: '',
		trackings: [],
		list: {
			icon: {
				show: 'chevron-right',
				hide: 'chevron-down'
			},
			states: []
		},
		tag: {
			text: {
				InfoReceived: 'Info Received',
				InTransit: 'In Transit',
				OutForDelivery: 'Out For Delivery',
				Delivered: 'Delivered',
				AttemptFail: 'Attempt Fail',
				Exception: 'Exception',
				Pending: 'Pending'
			},
			color: {
				InfoReceived: '#214977',
				InTransit: '#3498DB',
				OutForDelivery: '#E77F11',
				Delivered: '#27AE60',
				AttemptFail: '#9B59B6',
				Exception: '#C0392B',
				Pending: '#BDC3C7'
			}
		},
		isLoading: false
	},
	computed: {
		isSearchMode: function isSearchMode() {
			return this.trackings.length === 0;
		}
	},
	methods: {
		search: function search() {
			var numbers = this.$data.inputNumbers.split('\n');

			for (var i = 0; i < numbers.length; i++) {
				this.searchTracking(numbers[i]);
			}
		},
		searchTracking: function searchTracking(number) {

			if (!number || number === '') return;

			var self = this;
			//const baseUrl = api_base_url;
			var baseUrl = 'http://52.76.175.33:20061/trackings/';
			var config = {
				headers: { 'Api-Token': 'u2wW5v2dbrH98dApzZyDFS5xQuQvgvTP' }
			};
			self.$data.isLoading = true;
			axios.get(baseUrl + number, config).then(function (response) {

				self.$data.isLoading = false;

				if (response.data.status !== 200) {
					//return response.data.status;
					alert(response.data.error[0].messages);
					//alert(response.data);
					return;
				}

				var tracking = self.parseTracking(response.data.data);

				self.$data.trackings.push(tracking);

				self.$data.list.states = self.getList(self.$data.trackings.length);
			}).catch(function (error) {
				console.log(error);
			});
		},
		parseTracking: function parseTracking(data) {
			var renameKeys = function renameKeys(tracking) {
				return {
					olsCode: tracking.ols_key,
					salesRecordNumber: tracking.sales_record_number,
					courier: tracking.courier,
					trackingNumber: tracking.tracking_number,
					slug: tracking.slug,
					aftership_id: tracking.aftership_id,
					checkpoints: tracking.checkpoints.reverse()
				};
			};
			var groupCheckpoints = function groupCheckpoints(checkpoints) {
				var groupedCheckpoints = {};
				for (var i = 0; i < checkpoints.length; i++) {
					var checkpoint = checkpoints[i];
					var date = checkpoint.date;

					if (!groupedCheckpoints[date]) groupedCheckpoints[date] = [];

					groupedCheckpoints[date].push(checkpoint);
				}
				return groupedCheckpoints;
			};

			var tracking = renameKeys(data);
			tracking.latest_checkpoint = tracking.checkpoints[0];
			tracking.checkpoints = groupCheckpoints(tracking.checkpoints);

			return tracking;
		},
		getList: function getList(length) {
			var list = [];
			for (var i = 0; i < length; i++) {
				list.push({
					isFold: true,
					icon: this.$data.list.icon.show
				});
			}
			if (length === 1) {
				list[0].isFold = false;
				list[0].icon = this.$data.list.icon.hide;
			}

			return list;
		},
		toggleList: function toggleList(index) {
			var list = this.$data.list.states[index];
			if (list.isFold) {
				list.icon = this.$data.list.icon.hide;
			} else {
				list.icon = this.$data.list.icon.show;
			}
			list.isFold = !list.isFold;
		},
		getTagStyle: function getTagStyle(tag) {
			var color = this.$data.tag.color[tag];
			return {
				color: color,
				fontWeight: 'bold'
				// border: '1px solid ' + color
			};
		},
		switchToSearchMode: function switchToSearchMode() {
			this.$data.trackings = [];
		}
	}
});

/***/ }),
/* 3 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 4 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);