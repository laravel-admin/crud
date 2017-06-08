<template>
	<div class="form-group">
		<label :for="settings.id" class="col-sm-3 control-label">{{ settings.name }}</label>
		<div class="col-sm-9">
			<textarea :name="settings.id" rows="4" class="form-control" :value="data" @input="$emit('update', settings.id, $event.target.value, index)"></textarea>
		</div>
	</div>
</template>

<script>
    export default {
		props: ['settings','data','index']
    }
</script>
