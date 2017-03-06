import * as Vue from "vue";
import {PasswordHelper} from "./../../../helpers/password.helper";
import {} from "./../../../require";

export default {
	data() {
		let path = window.location.pathname;
		let baseUrl = path.substring(0, path.lastIndexOf("/")) + "/";
		return {
			baseUrl,
			adminManager: {
				create: false,
				email: "",
				password: "",
			},
			db: {
				driver: "mysql",
				host: "127.0.0.1",
				name: "simple_panel",
				password: "",
				port: "3306",
				table_prefix: "s_panel_",
				user: "root",
			},
			devMode: false,
			formStatus: {
				complete: false,
				disabled: false,
				warning: false,
				warningMessage: null,
			},
			message: {
				content: "",
				show: false,
				type: "info",
			},
			migrations: {
				creationInterface: false,
				runInterface: false,
				runRoute: true,
			},
			panelName: "SimplePanel",
			session: {
				prefix: "s_panel_",
			},
			systemAdmin: {
				login: "sysadmin",
				password: PasswordHelper.generatePassword(),
			},
		};
	},
	computed: {
		isSqlite() {
			return this["db"].driver === "sqlite";
		},
		homePath() {
			return window.location.pathname;
		},
	},
	methods: {
		setConfig() {
			let self = this;
			let config = JSON.parse(JSON.stringify(self.$data));
			delete config["formStatus"];
			delete config["message"];
			self["formStatus"].disabled = true;
			self["$http"]
				.post("?r=install&action=storeConfig", config)
				.then(({ body }) => {
					if (typeof body["status"] === "undefined" || !body["status"]) {
						self["formStatus"].disabled = false;
						self["message"].type = "warning";
						self["message"].content = body["message"] ? body["message"] : "Неизвестная ошибка!";
						self["message"].show = true;
					} else {
						self["formStatus"].complete = true;
						if (body["status"] === -1) {
							self["formStatus"].warning = true;
							if (body["message"]) {
								self["formStatus"].warningMessage = body["message"];
							}
						}
					}
				}, (response) => {
					self["formStatus"].disabled = false;
					self["message"].type = "warning";
					self["message"].content = "Неизвестная ошибка!";
					self["message"].show = true;
				});
		},
		hideMessage() {
			this["message"].show = false;
		},
	},
	template: <string> require("./template.pug"),
	watch: {
		"db.driver" (newValue, oldValue) {
			if (newValue !== oldValue) {
				if (newValue === "sqlite") {
					this["db"].name = "db/db.sqlite";
				} else {
					this["db"].name = "db_name";
				}
			}
		},
	},
} as Vue.ComponentOptions<Vue>;
