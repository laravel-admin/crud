<template>
		<div class="form-group">
				<div class="col-sm-9 col-sm-push-3 checkbox">
						<label><input type="checkbox" :name="settings.id" :checked="checked" @click="$emit('update', settings.id, $event.target.checked, index)"/> {{ settings.name }}</label>
					
						<p v-if="settings.description" style="padding-top:6px;">{{ settings.description }}</p>
				</div>
		</div>
</template>

<script>
    export default {
				
				props: ['settings', 'data', 'index', 'watcher_index'],

				data() {
            return {
								checked: this.data ? true : false
            }
				},
				
				watch : {
						watcher_index : function () {
								console.log('update boolean');
								this.checked = this.data ? true : false
            }
	    	}			
    }
</script>
