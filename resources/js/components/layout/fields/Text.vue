<template>
	<div class="form-group">
		<label :for="settings.id" class="col-sm-3 control-label">{{ settings.name }}</label>
		<div class="col-sm-9">
			<input type="text" :name="settings.id" class="form-control" :value="data" @input="$emit('update', settings.id, $event.target.value, index)"/>
			<p v-if="settings.description" style="padding-top:6px;">{{ settings.description }}</p>
		</div>
	</div>
</template>

<script>
    export default {

		props: ['settings', 'data', 'index', 'watcher_index'],

		mounted() {
            this.$emit('update', this.settings.id, (this.data) ? this.data : null, this.index);
        },
    }
</script>
