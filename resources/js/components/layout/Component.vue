<template>
    <div class="panel panel-default">
		<div class="panel-heading nav navbar-default">
            <div>
                <div class="pull-left">
                     <h3 class="panel-title">{{ data.settings.name }}</h3>
                </div>

                <div>
                    <ul class="nav navbar-nav navbar-right">
						<li :class="{'active':view=='content'}">
							<a href="#" @click.prevent="setView('content')">Content</a>
						</li>

						<li :class="{'active':view=='settings'}">
							<a href="#" @click.prevent="setView('settings')">Settings</a>
						</li>

                        <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></a>

                            <ul class="dropdown-menu">
								<li v-if="index"><a href="#" class="" @click.prevent="$emit('moveup', index)">Move up</a></li>
								<li v-if="index < length-1"><a href="#" class="" @click.prevent="$emit('movedown', index)">Move down</a></li>
								<li><a href="#" class="" @click.prevent="$emit('delete', index)">Delete</a></li>
								<li role="separator" class="divider"></li>
								<li v-for="component in components"><a href="#"  v-on:click.prevent="$emit('append', component, index)">Add {{ component.name }}</a></li>
                    		</ul>
                    	</li>
                    </ul>
                </div>

            </div>
        </div>

		<div class="panel-body" v-show="view == 'content'">
			<template v-for="field in settings.fields">
				<component :is="field.type" :settings="field" :data="getDataForField(field.id)" @update="updateContentField"></component>
			</template>
		</div>

		<div class="panel-body" v-show="view == 'settings'">
			<template v-for="field in componentSettings">
				<component :is="field.type" :settings="field" :data="data.settings[field.id]" @update="updateSettingsField"></component>
			</template>
		</div>

    </div>
</template>

<script>

    export default {
		//	Load all field types
		components: {
			'layout-text': require('./fields/Text.vue'),
	        'layout-textarea': require('./fields/Textarea.vue'),
	        'layout-boolean': require('./fields/Boolean.vue'),
	        'layout-select': require('./fields/Select.vue'),
	        'layout-media-item': require('./fields/MediaItem.vue'),
	        'layout-wysiwyg': require('./fields/Wysiwyg.vue'),
	        'layout-repeater': require('./fields/Repeater.vue'),
		},

		//	Define the props, given from the layout component
		props: ['components','data','settings','index','locale','length'],

		data()
		{
			return {
					//	Current view
					view:null,

					//	The default settings for each component
					componentSettings: [
						{id:'name', name:'Name', type:'layout-text'},
						{id:'active', name:'Active?', type:'layout-boolean'},
					]
			};
		},

		methods: {

			/**
			 * Toggle the view of a component between content, settings and blank
			 * @param string view
			 */
			setView(view)
			{
				this.view = (this.view == view) ? null : view;
			},

			/**
			 * Check if the data object has already has a property for the specific field
			 * @param string id
			 */
			getDataForField(id)
			{
				if (typeof this.data.content === 'undefined') return null;
				if (typeof this.data.content[id] === 'undefined') return null;

				return this.data.content[id];
			},

			/**
			 * Update the value of the by id given content field
			 * @param string id
			 * @param mixed data
			 */
			updateContentField(id, data)
			{
				//	Broadcast an event to the layout to update the data in the original object
				this.$emit('update', this.index, 'content', id, data);
			},

			/**
			 * Update the value of the by id given settings field
			 * @param string id
			 * @param mixed data
			 */
			updateSettingsField(id, data)
			{
				//	Broadcast an event to the layout to update the data in the original object
				this.$emit('update', this.index, 'settings', id, data);
			}
		}
    }
</script>
