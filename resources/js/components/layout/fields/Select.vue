<template>
	<div class="form-group">
		<label :for="settings.id" class="col-sm-3 control-label">{{ settings.name }}</label>
		<div class="col-sm-9">
            <select class="form-control" @change="$emit('update', settings.id, $event.target.value, index)">
                <option value="">---</option>
                <option v-for="(label,value) in settings.options" :value="value" v-text="label" :selected="data == value"></option>
            </select>
            <p v-if="settings.description" style="padding-top:6px;">{{ settings.description }}</p>
		</div>
	</div>
</template>

<script>
    export default {
		props: ['settings', 'data', 'index', 'watcher_index']
    }
</script>
