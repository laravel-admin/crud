<template>
	<div class="form-group">
		<div class="col-sm-9 col-sm-push-3 checkbox">
			<label><input type="checkbox" :name="settings.id" :checked="this.data?true:false" @click="$emit('update', settings.id, $event.target.checked, index)"/> {{ settings.name }}</label>
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
