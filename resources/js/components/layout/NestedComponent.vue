<template>
    
	<div class="panel panel-default">
	
		<div class="panel-heading">

			<div class="btn-group pull-right">
				
				<button type="button" class="btn btn-default btn-xs" :class="{'active':view=='content'}" @click.prevent="setView('content')">
					Content
				</button>

				<div class="btn-group">
					
					<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Action <span class="caret"></span>
					</button>

					<ul class="dropdown-menu">
						<li v-if="index"><a href="#" class="" @click.prevent="$emit('moveup', index)">Move up</a></li>
						<li v-if="index < length-1"><a href="#" class="" @click.prevent="$emit('movedown', index)">Move down</a></li>
						<li><a href="#" class="" @click.prevent="$emit('delete', index)">Delete</a></li>
						<li role="separator" class="divider"></li>
						<li v-for="component in components"><a href="#"  v-on:click.prevent="$emit('append', component, index)">Add {{ component.name }}</a></li>
					</ul>

				</div>

			</div>

			<h3 class="panel-title">{{ settings.name }}</h3>

        </div>

		<div class="panel-body" v-show="view == 'content'">
			<template v-for="field in settings.fields">
				<component :is="field.type" :settings="field" :data="getDataForField(field.id)" :watcher_index="1" :index="index" @update="updateContentField"></component>
			</template>
		</div>

    </div>
</template>

<script>
    export default {
		//	Load all field types
		components: {
	        'layout-boolean': require('./fields/Boolean.vue'),
			'layout-date': require('./fields/Date.vue'),
	        'layout-media-item': require('./fields/MediaItem.vue'),
	        'layout-repeater': require('./fields/Repeater.vue'),
	        'layout-select': require('./fields/Select.vue'),
			'layout-text': require('./fields/Text.vue'),
	        'layout-textarea': require('./fields/Textarea.vue'),
	        'layout-wysiwyg': require('./fields/Wysiwyg.vue'),
		},

		//	Define the props, given from the layout component
		props: ['data','settings','index','length', 'components'],

		data()
		{
			return {
				//	Current view
				view:null,
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
			}
		}
    }
</script>
