(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Base', ['exports', 'jquery', 'Component', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Component'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Component, global.Plugin);
    global.Base = mod.exports;
  }
})(this, function (exports, _jquery, _Component2, _Plugin) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Component3 = babelHelpers.interopRequireDefault(_Component2);

  var Base = function (_Component) {
    babelHelpers.inherits(Base, _Component);

    function Base() {
      babelHelpers.classCallCheck(this, Base);
      return babelHelpers.possibleConstructorReturn(this, (Base.__proto__ || Object.getPrototypeOf(Base)).apply(this, arguments));
    }

    babelHelpers.createClass(Base, [{
      key: 'initializePlugins',
      value: function initializePlugins() {
        // console.log(arguments);
        var context = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

        (0, _jquery2.default)('[data-plugin]', context || this.$el).each(function () {
           
            var $this = (0, _jquery2.default)(this),
                name =$this.data('plugin');
            var pname = name.split(',');
            for(var pn in pname)
            {
              var  plugin = (0, _Plugin.pluginFactory)(pname[pn], $this, $this.data());

              // console.log($this.data());
              if (plugin) {
                plugin.initialize();
              }
            }
            
          
        });
      }
    }, {
      key: 'initializePluginAPIs',
      value: function initializePluginAPIs() {
        var context = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : document;

        var apis = (0, _Plugin.getPluginAPI)();

        for (var name in apis) {
          // console.log(name);
          (0, _Plugin.getPluginAPI)(name)('[data-plugin=' + name + ']', context);
        }
      }
    }]);
    return Base;
  }(_Component3.default);

  exports.default = Base;
});