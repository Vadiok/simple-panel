import * as Vue from "vue";
import {} from "./../../../../../../src/scripts/require";

export default {
	computed: {
		lastBatchCount() {
			let count = 0;
			this["appliedMigrations"].forEach((value, index) => {
				if (value["in_last_batch"]) {
					count++;
				}
			});
			return count;
		},
		lastBatchFirstIndex() {
			let lastBatchFirstIndex = null;
			this["appliedMigrations"].forEach((value, index) => {
				if ((lastBatchFirstIndex === null) && value["in_last_batch"]) {
					lastBatchFirstIndex = index;
				}
			});
			return lastBatchFirstIndex;
		},
	},
	data() {
		return {
			collapseContent: false,
			migrationExecuting: false,
		};
	},
	methods: {
		executeAll() {
			this["migrationExecuting"] = true;
			this["$http"]
				.post("?r=migrations/execute")
				.then(({ body }) => {
					this["migrationExecuting"] = false;
					let message;
					let messageStatus = null;
					if (typeof body["status"] === "undefined") {
						this.$emit("show-message", "Неизвестная ошибка", "warning");
						return;
					}
					message = body["message"];
					if (!body["status"] || (body["status"] === -1)) {
						messageStatus = "warning";
					} else {
						messageStatus = "success";
						if (!message) {
							message = "Миграции выполнены";
						}
					}
					if (typeof body["data"] !== "undefined" && body["data"]) {
						this.$emit("update-migrations-list", body["data"]);
					}
					this.$emit("show-message", message, messageStatus);
				}, (response) => {
					this["migrationExecuting"] = false;
					this.$emit("show-message", "Неизвестная ошибка", "warning");
				});
		},
		executeMigration(file) {
			this["migrationExecuting"] = true;
			this["$http"]
				.post("?r=migrations/execute", {file})
				.then(({ body }) => {
					this["migrationExecuting"] = false;
					let message;
					let messageStatus = null;
					if (typeof body["status"] === "undefined") {
						this.$emit("show-message", "Неизвестная ошибка", "warning");
						return;
					}
					message = body["message"];
					if (!body["status"] || (body["status"] === -1)) {
						messageStatus = "warning";
					} else {
						messageStatus = "success";
						if (!message) {
							message = "Миграции выполнена";
						}
					}
					if (typeof body["data"] !== "undefined" && body["data"]) {
						this.$emit("update-migrations-list", body["data"]);
					}
					this.$emit("show-message", message, messageStatus);
				}, (response) => {
					this["migrationExecuting"] = false;
					this.$emit("show-message", "Неизвестная ошибка", "warning");
				});
		},
		rollbackLastBranch() {
			let confirmMessage = "Вы уверены, что хотите откатить последние выполненные миграции?\n" +
				"Возможно, некоторые данные будут удалены без возможности восстановления!";
			if (confirm(confirmMessage)) {
				this["migrationExecuting"] = true;
				this["$http"]
					.post("?r=migrations/rollback")
					.then(({body}) => {
						this["migrationExecuting"] = false;
						let message;
						let messageStatus = null;
						if (typeof body["status"] === "undefined") {
							this.$emit("show-message", "Неизвестная ошибка", "warning");
							return;
						}
						message = body["message"];
						if (!body["status"] || (body["status"] === -1)) {
							messageStatus = "warning";
						} else {
							messageStatus = "success";
							if (!message) {
								message = "Откат выполнен";
							}
						}
						if (typeof body["data"] !== "undefined" && body["data"]) {
							this.$emit("update-migrations-list", body["data"]);
						}
						this.$emit("show-message", message, messageStatus);
					}, (response) => {
						this["migrationExecuting"] = false;
						this.$emit("show-message", "Неизвестная ошибка", "warning");
					});
			}
		},
	},
	mixins: [
		require("./../../mixins/migrations-list.mixin")["default"],
	],
	props: [
		"appliedMigrations",
		"unAppliedMigrations",
		"listLoading",
	],
	template: <string> require("./template.pug"),
} as Vue.ComponentOptions<Vue>;
