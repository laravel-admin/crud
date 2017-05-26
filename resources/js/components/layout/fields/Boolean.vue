<template>
	<div class="form-group">
		<label :for="settings.id" class="col-sm-3 control-label">{{ settings.name }}</label>
		<div class="col-sm-9">
			<input type="checkbox" :name="settings.id" :checked="data ? true : false" @click="$emit('update', settings.id, $event.target.checked)"/>
		</div>
	</div>
</template>

<script>
    export default {
		props: ['settings','data']
    }
</script>
