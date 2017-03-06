import * as Vue from "vue";

export default {
	methods: {
		toggleContent() {
			this["collapseContent"] = !this["collapseContent"];
		},
	},
	computed: {
		appliedCount() {
			if (typeof this["appliedMigrations"] === "undefined") return 0;
			return this["appliedMigrations"].length;
		},
		unAppliedCount() {
			if (typeof this["unAppliedMigrations"] === "undefined") return 0;
			return this["unAppliedMigrations"].length;
		},
	},
} as Vue.ComponentOptions<Vue>;
