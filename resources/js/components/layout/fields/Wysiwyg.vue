<template>
	<div class="form-group">
        <label :for="settings.id" class="col-sm-3 control-label">{{ settings.name }}</label>
        <div class="col-sm-9">
            <tinymce :id="makeid" :options="options" @change="updateData" :content="content"></tinymce>
            <p v-if="settings.description" style="padding-top:6px;">{{ settings.description }}</p>
        </div>
	</div>
</template>

<script>
    export default {
		props: ['settings', 'data', 'index', 'watcher_index'],

        data() {
            return {
                content: (!this.data || typeof this.data == 'object') ? '' : this.data,
                options: {
                    height : "200",
                    plugins: ['lists', 'link', 'code', 'charmap', 'paste'],
                    menubar: '',
                    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | charmap | link unlink | removeformat | code',
                }
            }
        },

        watch : {
            watcher_index : function () {
                console.log('update wysiwyg');
                this.content = (!this.data || typeof this.data == 'object') ? '' : this.data;
            }
	    },        

        computed: {

            makeid()
            {
                var text = "";
                var possible = "abcdefghijklmnopqrstuvwxyz";

                for( var i=0; i < 10; i++ )
                    text += possible.charAt(Math.floor(Math.random() * possible.length));

                return text;
            }

        },

        methods: {

            updateData(editor, content) {
                this.$emit('update', this.settings.id, content, this.index);
            }

        }
    }
</script>
