<template>
	<div class="form-group">
		<label :for="settings.id" class="col-sm-3 control-label">{{ settings.name }}</label>
		<div class="col-sm-9">
			<media-item controller="/media/ajax" :filetypes="settings.filetypes" :item="item" @update-media-item="updateMediaItem"></media-item>
			<p v-if="settings.description" style="padding-top:6px;">{{ settings.description }}</p>
		</div>
	</div>
</template>

<script>
    export default {

		props: ['settings', 'data', 'index', 'watcher_index'],

        data() {
            return {item:null};
        },

        mounted() {
            this.getMediaItem();
        },

		watch : {
			watcher_index : function () {
				console.log('update mediaitem');
				this.getMediaItem();
			}
	    },

        methods: {

            updateMediaItem(obj) {
                this.$emit('update', this.settings.id, obj ? obj.id : null, this.index);
            },

			getMediaItem() {
				if (this.data) {
	                axios({url:'/media/ajax/'+this.data, method:'get'}).then(response => {
	                    this.item = response.data;
	                });
	            } else {
					this.item = null;
				}
			}

        }

    }
</script>
