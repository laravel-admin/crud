<template>

    <div v-if="settings">

		<div class="panel panel-default">

			<div class="panel-heading">
                
				<div class="btn-group pull-right" v-show="this.hastranslation=='true'">
                    
					<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Add <span class="caret"></span>
					</button>

					<ul class="dropdown-menu">
						<li v-for="component in settings.components"><a href="#" class="" v-on:click.prevent="appendComponent(component)">{{ component.name }}</a></li>
					</ul>

                </div>

				<h3 class="panel-title">{{ this.language }} layout setup</h3>

            </div>

			<div class="panel-body" v-show="this.hastranslation=='false'">
				
				<p>Please use the content setup for the selected language first.</p>

			</div>

			<div class="panel-body" v-show="this.hastranslation=='true'">

				<layout-component
					v-for="(component,key) in checkedData"
					:key="key"
					:index="key"
					:data="component"
					:locale="locale"
					:length="checkedData.length"
					:components="settings.components"
					:settings="getSettingsForComponent(component.settings.type)"
					@update="updateComponent"
					@append="appendComponent"
					@delete="deleteComponent"
					@moveup="moveComponentUp"
					@movedown="moveComponentDown"
				></layout-component>

			</div>

		</div>

    </div>

</template>

<script>
import Event from '../../../../../base/resources/js/Event';

    export default {

		components: {
			'layout-component': require('./Component.vue'),
        },

		props: ['layoutdata', 'layoutsettings', 'hastranslation', 'language', 'locale', 'controller'],

		data()
		{
			return {
				//	Initialise settings object, who will be filled by an ajax request
				settings: this.layoutsettings,

				//	The data of the current object
				data: this.addWatcherIndexToLayoutData(),
			};
		},

        mounted() {
            console.log('Layout component mounted.');
        },

		computed: {

            checkedData()
            {
                return this.data.filter(item => {
                    if (typeof item.settings.type == 'undefined') {
                        return false;
					}

					return this.getSettingsForComponent(item.settings.type) ? true : false;
                });
            }

		},

		methods:
		{
			generateUniqueId(val){
				return (Math.random() * (val+1)).toString(36).substr(2, 16);
			},

			addWatcherIndexToLayoutData() {
				let settings = this.layoutdata;
				
				settings.forEach((item, index) => {
					settings[index].watcher_index = this.generateUniqueId(index);
				});

				return settings;
			},

			/**
			 * Append a component to the layout
			 * @param object component
			 * @param int index
			 */
			appendComponent(component, index)
			{
				//	Prototype for the new data object
				let obj = {
					settings: {
						name: component.name,
						active: true,
						type: component.id,
					},
					watcher_index: this.generateUniqueId(index+1)
				};

				this.data.push(obj);

				// TODO: Append new component to the specified index

				//	If no index specified, append the component to the bottom
				//if (typeof index === 'undefined') this.data.push(obj);

				//	Append the component at the specified position
				//else this.data.splice(index, 0, obj);
			},

			/**
			 * Delete a component from the layout
			 * @param int index
			 */
			deleteComponent(index)
			{
				//	Delete the component with the given index of the array
				this.data.splice(index,1);
			},

			moveComponentDown(index)
			{
				if (typeof this.data[index+1] == 'undefined') {
					return;
				}

				let item = this.data[index];

				this.data.splice(index,1);
				this.data.splice(index+1, 0, item);
			},

			moveComponentUp(index)
			{
				if (typeof this.data[index-1] == 'undefined') {
					return;
				}

				let item = this.data[index];

				this.data.splice(index,1);
				this.data.splice(index-1, 0, item);
			},

			/**
			 * Get the settings for the component wich has to be rendered
			 * @param string type
			 */
			getSettingsForComponent(type)
			{
				//	Search in all defined componenttypes for the one with an id as type
				for (let i in this.settings.components)
				{
					if (this.settings.components[i].id == type)
					{
						return this.settings.components[i];
					}
				}

				//	Return null if component type is not found
				return null;
			},

			/**
			 * Update a single value of the content of settings of a particular component
			 * @param int index
			 * @param string section
			 * @param string field
			 * @param mixed data
			 */
			updateComponent(index, section, field, data)
			{
				//	Does a component with the specified index exists?
				if (typeof this.data[index] === 'undefined') this.data[index] = {};

				//	Does the section (content or setttings) exists on the component
				if (typeof this.data[index][section] === 'undefined') this.data[index][section] = {};

				//	Update the data of the field
				this.data[index][section][field] = data;
			},

			save()
			{
                const notify = () => {
                    Event.$emit('notification', {type: 'success', message: 'The layout is succesfully saved.'});
                };
				const flash_error = (data) => {
					let message = data.message;
					for (var key in data.errors) {
						if (data.errors.hasOwnProperty(key)) {
							message = data.errors[key].toString();
							break;
						}
					}
					Event.$emit('notification', {type: 'danger', message: message});
				};
				if (this.locale) {
					axios.put(this.controller, {layout:this.checkedData}).then(notify).catch(error => {
						flash_error(error.response.data);
					});
				}
				else {
					axios.post(this.controller, {layout:this.checkedData}).then(notify).catch(error => {
						flash_error(error.response.data);
					});
				}
			}
		}
    }
</script>
