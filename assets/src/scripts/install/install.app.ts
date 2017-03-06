import * as Vue from "vue";
import VueResource from "vue-resource";
import {} from "./../require";

Vue.use(VueResource);

export default new Vue({
	components: {
		"initial-config": require("./components/initial-config/initial-config.component")["default"],
	},
	data: {},
	el: "#panel-system-install",
});
