<template>
	<div class="form-group">
		<label :for="settings.id" class="col-sm-3 control-label">{{ settings.name }}</label>
		<div class="col-sm-9">
			<input type="text" :name="settings.id" class="form-control" :value="data" @input="$emit('update', settings.id, $event.target.value)"/>
		</div>
	</div>
</template>

<script>
    export default {
		props: ['settings','data']
    }
</script>
