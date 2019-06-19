<template>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="btn-group pull-right">
        <button
          type="button"
          class="btn btn-default btn-xs"
          :class="{'active':view=='content'}"
          @click.prevent="setView('content')"
        >Content</button>

        <button
          type="button"
          class="btn btn-default btn-xs"
          :class="{'active':view=='settings'}"
          @click.prevent="setView('settings')"
        >Settings</button>

        <div class="btn-group">
          <button
            type="button"
            class="btn btn-default btn-xs dropdown-toggle"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            Action
            <span class="caret"></span>
          </button>

          <ul class="dropdown-menu">
            <li v-if="index">
              <a href="#" class @click.prevent="$emit('moveup', index)">Move up</a>
            </li>
            <li v-if="index < length-1">
              <a href="#" class @click.prevent="$emit('movedown', index)">Move down</a>
            </li>
            <li>
              <a href="#" class @click.prevent="$emit('delete', index)">Delete</a>
            </li>
            <li role="separator" class="divider"></li>
            <li v-for="component in components">
              <a
                href="#"
                v-on:click.prevent="$emit('append', component, index)"
              >Add {{ component.name }}</a>
            </li>
          </ul>
        </div>
      </div>

      <h3 class="panel-title">{{ data.settings.name }}</h3>
    </div>

    <div class="panel-body" v-show="view == 'content'">
      <template v-for="(field, key) in settings.fields">
        <component
          :is="field.type"
          :settings="field"
          :data="getDataForField(field.id)"
          :watcher_index="data.watcher_index"
          :key="generateUniqueId('content', key)"
          :index="index"
          @update="updateContentField"
        ></component>
      </template>
    </div>

    <div class="panel-body" v-show="view == 'settings'">
      <template v-for="(field, key) in componentSettings">
        <component
          :is="field.type"
          :settings="field"
          :data="data.settings[field.id]"
          :watcher_index="data.watcher_index"
          :key="generateUniqueId('setting', key)"
          :index="index"
          @update="updateSettingsField"
        ></component>
      </template>
    </div>
  </div>
</template>

<script>
import FieldBoolean from './fields/Boolean.vue';
import FieldDate from './fields/Date.vue';
import FieldComponentRepeater from './fields/ComponentRepeater.vue';
import FieldMediaItem from './fields/MediaItem.vue';
import FieldRepeater from './fields/Repeater.vue';
import FieldSelect from './fields/Select.vue';
import FieldText from './fields/Text.vue';
import FieldTextarea from './fields/Textarea.vue';
import FieldWysiwyg from './fields/Wysiwyg.vue';

export default {
    //	Load all field types
    components: {
        'layout-boolean': FieldBoolean,
        'layout-date': FieldDate,
        'layout-component-repeater': FieldComponentRepeater,
        'layout-media-item': FieldMediaItem,
        'layout-repeater': FieldRepeater,
        'layout-select': FieldSelect,
        'layout-text': FieldText,
        'layout-textarea': FieldTextarea,
        'layout-wysiwyg': FieldWysiwyg,
    },

    //	Define the props, given from the layout component
    props: ['components', 'data', 'settings', 'index', 'locale', 'length'],

    data() {
        return {
            //	Current view
            view: null,

            //	The default settings for each component
            componentSettings: [
                { id: 'name', name: 'Name', type: 'layout-text' },
                { id: 'active', name: 'Active?', type: 'layout-boolean' },
            ],
        };
    },

    methods: {
        /**
         * Create component unique id
         * @param string type
         * @param int key
         */
        generateUniqueId(type, key) {
            return type + key + this.data.watcher_index;
        },

        /**
         * Toggle the view of a component between content, settings and blank
         * @param string view
         */
        setView(view) {
            this.view = this.view == view ? null : view;
        },

        /**
         * Check if the data object has already has a property for the specific field
         * @param string id
         */
        getDataForField(id) {
            if (typeof this.data.content === 'undefined') return null;
            if (typeof this.data.content[id] === 'undefined') return null;

            return this.data.content[id];
        },

        /**
         * Update the value of the by id given content field
         * @param string id
         * @param mixed data
         */
        updateContentField(id, data) {
            //	Broadcast an event to the layout to update the data in the original object
            this.$emit('update', this.index, 'content', id, data);
        },

        /**
         * Update the value of the by id given settings field
         * @param string id
         * @param mixed data
         */
        updateSettingsField(id, data) {
            //	Broadcast an event to the layout to update the data in the original object
            this.$emit('update', this.index, 'settings', id, data);
        },
    },
};
</script>
