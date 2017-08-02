<template>
	<div class="form-group">
		<div class="col-sm-9 col-sm-push-3 checkbox">
			<label><input type="checkbox" :name="settings.id" :checked="data ? true : false" @click="$emit('update', settings.id, $event.target.checked)"/> {{ settings.name }}</label>
		</div>
	</div>
</template>

<script>
    export default {
		props: ['settings','data']
    }
</script>
