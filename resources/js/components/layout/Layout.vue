<template>
    <div v-if="settings">

		<div class="panel panel-default">
			<div class="panel-heading nav navbar-default">
                <div>
                    <ul class="nav navbar-nav navbar-left">

                        <li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Add <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li v-for="component in settings.components"><a href="#" class="" v-on:click.prevent="appendComponent(component)">{{ component.name }}</a></li>
                    		</ul>
                    	</li>

						<li>
							<a href="#" @click.prevent="save">Save</a>
						</li>
                    </ul>
                </div>
            </div>
	    </div>

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
</template>

<script>
import Event from '../../../../../base/resources/js/Event';

    export default {

		components: {
			'layout-component': require('./Component.vue'),
        },

		props: ['layoutdata', 'layoutsettings', 'locale', 'controller'],

		data()
		{
			return {
				//	Initialise settings object, who will be filled by an ajax request
				settings:this.layoutsettings,

				//	The data of the current object
				//	TODO: This data has to be fetch from the HTML or with ajax
				data: this.layoutdata,
			};
		},

        mounted() {
            console.log('Layout component mounted.');

			//	Get the settings for the layout module
			//axios.get(this.controller.substr(0,-2)+'create').then(response => this.settings = response.data);
			//axios.get(this.controller).then(response => this.data = response.data.layout ? response.data.layout : []);

        },

		computed: {

            checkedData()
            {
                return this.data.filter(item => {
                    if (typeof item.settings.type == 'undefined') {
                        return false;
                    }

                    return  this.getSettingsForComponent(item.settings.type) ? true : false;
                });
            }

		},

		methods:
		{
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
					}
				};

				//	If no index specified, append the component to the bottom
				if (typeof index === 'undefined') this.data.push(obj);

				//	Append the component at the specified position
				else this.data.splice(index, 0, obj);
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
                    Event.$emit('notification', {type: 'success', message: 'Saved'});
                };
                const error = () => {
                    Event.$emit('notification', {type: 'danger', message: 'Saving failed'});
                };
				if (this.locale) {
					axios.put(this.controller, {layout:this.checkedData}).then(notify).catch(error);
				}
				else {
					axios.post(this.controller, {layout:this.checkedData}).then(notify).catch(error);
				}
			}
		}
    }
</script>

<style>

/* CSS used here will be applied after bootstrap.css */
/*
Using default Bootstrap 3 classes we zero out the top and
bottom padding .panel-heading ususally needs
*/
.panel-heading.nav.navbar-default{padding-top:0px;padding-bottom:0px;}

/*
Reintroduce 20px for .panel-title when a navbar is within .panel-heading.
This can be put back to @line-height-computed; in your LESS file which
is the default in type.less */
.panel-heading.nav.navbar-default h3{margin-top:20px;}
.panel-heading.nav.navbar-default h4{margin-top:15px;}
</style>
