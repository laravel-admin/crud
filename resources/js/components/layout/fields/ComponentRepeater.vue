<template>
  <div v-if="settings">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="btn-group pull-right">
          <button
            type="button"
            class="btn btn-default btn-xs dropdown-toggle"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            Add
            <span class="caret"></span>
          </button>

          <ul class="dropdown-menu">
            <li v-for="component in settings.children">
              <a href="#" class v-on:click.prevent="appendComponent(component)">{{ component.name }}</a>
            </li>
          </ul>
        </div>

        <h3 class="panel-title">Component repeater</h3>
      </div>

      <div class="panel-body">
        <layout-nested-component
          v-for="(component,key) in checkedData"
          :key="key"
          :index="key"
          :data="component"
          :length="checkedData.length"
          :components="settings.children"
          :settings="getSettingsForComponent(component.settings.type)"
          @update="updateComponent"
          @append="appendComponent"
          @delete="deleteComponent"
          @moveup="moveComponentUp"
          @movedown="moveComponentDown"
        ></layout-nested-component>
      </div>
    </div>
  </div>
</template>

<script>
import Event from '../../../../../../base/resources/js/Event';
import NestedComponent from '../NestedComponent.vue';

export default {
    components: {
        'layout-nested-component': NestedComponent,
    },

    props: ['settings', 'data', 'index', 'watcher_index'],

    data() {
        let output = {
            //	Initialise settings object, who will be filled by an ajax request
            repeater_settings: this.settings,

            //	The data of the current object
            repeater_data: this.addWatcherIndexToLayoutData(),
        };

        return output;
    },

    mounted() {
        console.log('ComponentRepeater component mounted.');
    },

    updated() {
        this.$emit('update', this.settings.id, this.checkedData ? this.checkedData : null, this.index);
    },

    computed: {
        checkedData() {
            return this.repeater_data.filter(item => {
                if (typeof item.settings == 'undefined') {
                    return false;
                }

                if (typeof item.settings.type == 'undefined') {
                    return false;
                }

                return this.getSettingsForComponent(item.settings.type) ? true : false;
            });
        },
    },

    methods: {
        generateUniqueId(val) {
            return (Math.random() * (val + 1)).toString(36).substr(2, 16);
        },

        addWatcherIndexToLayoutData() {
            let settings = this.data ? this.data : [];

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
        appendComponent(component, index) {
            //	Prototype for the new data object
            let obj = {
                settings: {
                    name: component.name,
                    type: component.id,
                },
                watcher_index: this.generateUniqueId(this.checkedData.length),
            };

            this.repeater_data.push(obj);

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
        deleteComponent(index) {
            //	Delete the component with the given index of the array
            this.repeater_data.splice(index, 1);
        },

        moveComponentDown(index) {
            if (typeof this.repeater_data[index + 1] == 'undefined') {
                return;
            }

            let item = this.repeater_data[index];

            this.repeater_data.splice(index, 1);
            this.repeater_data.splice(index + 1, 0, item);
        },

        moveComponentUp(index) {
            if (typeof this.repeater_data[index - 1] == 'undefined') {
                return;
            }

            let item = this.repeater_data[index];

            this.repeater_data.splice(index, 1);
            this.repeater_data.splice(index - 1, 0, item);
        },

        /**
         * Get the settings for the component wich has to be rendered
         * @param string type
         */
        getSettingsForComponent(type) {
            //	Search in all defined componenttypes for the one with an id as type
            for (let i in this.repeater_settings.children) {
                if (this.repeater_settings.children[i].id == type) {
                    return this.repeater_settings.children[i];
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
        updateComponent(index, section, field, data) {
            //	Does a component with the specified index exists?
            if (typeof this.repeater_data[index] === 'undefined') this.repeater_data[index] = {};

            //	Does the section (content or setttings) exists on the component
            if (typeof this.repeater_data[index][section] === 'undefined') this.repeater_data[index][section] = {};

            //	Does the field exists on the component
            if (typeof this.repeater_data[index][section][field] === 'undefined')
                this.repeater_data[index][section][field] = {};

            //	Update the data of the field
            this.repeater_data[index][section][field] = data;
        },
    },
};
</script>
