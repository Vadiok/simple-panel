import * as Vue from "vue";
import VueResource from "vue-resource";
import {} from "./../require";

Vue.use(VueResource);

export default new Vue({
	components: {
		"creation-interface": require("./components/creation-interface/creation-interface.component")["default"],
		"running-interface": require("./components/running-interface/running-interface.component")["default"],
	},
	computed: {
		unAppliedMigrations() {
			let result = [];
			this["migrationsList"].forEach((value) => {
				if (!value.executed) {
					result.push(value.name);
				}
			});
			return result;
		},
		appliedMigrations() {
			let result = [];
			this["migrationsList"].forEach((value) => {
				if (value.executed) {
					result.push(value);
				}
			});
			return result;
		},
	},
	data: {
		allowMigrationCreating:
			<boolean> (typeof window["allowMigrationCreating"] !== "undefined") ? window["allowMigrationCreating"] : false,
		allowMigrationRunning:
			<boolean> (typeof window["allowMigrationRunning"] !== "undefined") ? window["allowMigrationRunning"] : false,
		initialListLoaded: false,
		listLoading: false,
		message: {
			content: "",
			show: false,
			type: "info",
		},
		migrationsList: null,
	},
	el: "#panel-system-migrations",
	methods: {
		hideMessage() {
			this["message"].show = false;
		},
		showMessage(content, type = "info") {
			this["message"].type = type;
			this["message"].content = content;
			this["message"].show = true;
		},
		loadMigrationsListInfo(list = null) {
			let self = this;
			if (list) {
				self["migrationsList"] = list;
				return;
			}
			if (self["listLoading"]) {
				return;
			}
			self["listLoading"] = true;
			self["$http"]
				.get("?r=migrations/list")
				.then(({body}) => {
					if (!body.status) {
						self["message"].type = "warning";
						self["message"].content = "Ошибка получения списка миграций";
						self["message"].show = true;
						self["listLoading"] = false;
						return;
					}
					self["migrationsList"] = body.data;
					self["initialListLoaded"] = true;
					self["listLoading"] = false;
				}, () => {
					self["message"].type = "warning";
					self["message"].content = "Ошибка получения списка миграций";
					self["message"].show = true;
					self["listLoading"] = false;
				});
		},
	},
	mounted () {
		this["loadMigrationsListInfo"]();
	},
});
