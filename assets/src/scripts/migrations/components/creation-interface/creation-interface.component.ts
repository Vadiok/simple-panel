import * as Vue from "vue";
import {} from "./../../../../../../src/scripts/require";

const stringToSnakeCase = (str: string) => {
	str = str.replace(/_+/g, "_");
	str = str.replace(/_$/g, "");
	return str.replace(/([A-Z])/g, ($1) => "_" + $1.toLowerCase());
};

const stringToCamelCase = (str: string) => {
	str = str.replace(/_+/g, "_");
	str = str.replace(/_$/g, "");
	str = str.replace(/^([a-z])/, ($1) => $1.toUpperCase());
	return str.replace(/(_[a-z])/g, ($1) => $1.toUpperCase().replace("_", ""));
};

export default {
	computed: {
		switchersDisabled() {
			return !this["newMigration"].tableName.length;
		},
		formValid() {
			if (
				this["newMigrationFormErrors"].fileName ||
				this["newMigrationFormErrors"].tableName
			) {
				return false;
			}
			return (this["newMigration"].fileName.length > 5);
		},
		newMigrationClassName() {
			if (
				this["newMigrationFormErrors"].fileName ||
				!this["newMigration"].fileName.length
			) {
				return null;
			}
			return stringToCamelCase(this["newMigration"].fileName);
		},
		newMigrationFileName() {
			if (
				this["newMigrationFormErrors"].fileName ||
				!this["newMigration"].fileName.length
			) {
				return null;
			}
			return stringToSnakeCase(this["newMigration"].fileName);
		},
	},
	data() {
		return {
			collapseContent: false,
			formLoading: false,
			newMigration: {
				create: false,
				fileName: "",
				tableName: "",
				update: false,
			},
			newMigrationFormErrors: {
				fileName: null,
				tableName: null,
			},
		};
	},
	methods: {
		createMigrationFile() {
			this["formLoading"] = true;
			this["$http"]
				.post("?r=migrations/create", this["newMigration"])
				.then(({ body }) => {
					this["formLoading"] = false;
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
							message = "Миграция добавлена";
						}
					}
					if (typeof body["data"] !== "undefined" && body["data"]) {
						this.$emit("update-migrations-list", body["data"]);
					}
					this.$emit("show-message", message, messageStatus);
				}, (response) => {
					this["formLoading"] = false;
					this.$emit("show-message", "Неизвестная ошибка", "warning");
				});
		},
		removeFile(unApplied) {
			if (confirm(`Вы уверены, что хотите удалить невыполненную миграцию ${unApplied}?`)) {
				this["$http"]
					.post("?r=migrations", {
						_method: "delete",
						file: unApplied,
					})
					.then(({ body }) => {
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
								message = "Файл миграции успешно удален";
							}
						}
						if (typeof body["data"] !== "undefined" && body["data"]) {
							this.$emit("update-migrations-list", body["data"]);
						}
						this.$emit("show-message", message, messageStatus);
					}, (response) => {
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
	watch: {
		"newMigration.fileName"() {
			if (this["newMigration"].fileName.length <= 5) {
				this["newMigrationFormErrors"].fileName = "Слишком короткое название миграции";
				return;
			}
			if (!/^[a-z]/i.test(this["newMigration"].fileName)) {
				this["newMigrationFormErrors"].fileName =
					"Название миграции должно начинаться с символов латинского алфавита";
				return;
			}
			if (!/^[_a-z0-9]+$/i.test(this["newMigration"].fileName)) {
				this["newMigrationFormErrors"].fileName =
					`Допустимы символы латинского алфавита (в любом регистре), цифры, а также символ нижнего подчеркивания "_"`;
				return;
			}
			this["newMigrationFormErrors"].fileName = null;
		},
		"newMigration.tableName"() {
			if (this["switchersDisabled"]) {
				this["newMigration"].create = false;
				this["newMigration"].update = false;
			}
			if (!/^[-_0-9a-z]*$/i.test(this["newMigration"].tableName)) {
				this["newMigrationFormErrors"].tableName =
					`Допустимы символы латинского алфавита, цифры, а также символы "-" и "_"`;
				return;
			}
			this["newMigrationFormErrors"].tableName = null;
		},
	},
} as Vue.ComponentOptions<Vue>;
