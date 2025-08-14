<template>
  <div class="form-group">
    <label :for="settings.id" class="col-sm-3 control-label">{{ settings.name }}</label>
    <div class="col-sm-9">
      <!-- Create a panel for each iteration of the repeater -->
      <div class="panel panel-default" v-for="(item, key) in content">
        <div class="panel-body">
          <!-- Load for each child within the settings the specific component -->
          <template v-for="field in settings.children">
            <component
              :is="field.type"
              :index="key"
              :key="generateUniqueId(key, field.id)"
              :settings="field"
              :data="getDataForChild(key, field.id)"
              @update="updateChild"
            ></component>
          </template>
          <hr>
        </div>
        <!-- Action for each item -->
        <div class="panel-footer">
          <button class="btn btn-primary" @click.prevent="addItem(key, 'below')">Add</button>
          <button class="btn btn-danger" @click.prevent="deleteItem(key)">Delete</button>
          <button v-if="key" class="btn btn-default" @click.prevent="moveItemUp(key)">Move up</button>
          <button
            v-if="key < repeaterLength-1"
            class="btn btn-default"
            @click.prevent="moveItemDown(key)"
          >Move down</button>
        </div>
      </div>

      <!-- If no items avaliable just show an add item button -->
      <button class="btn btn-primary" @click.prevent="addItem" v-if="!content.length">Add item</button>
    </div>
  </div>
</template>

<script>
import FieldBoolean from './Boolean.vue';
import FieldDate from './Date.vue';
import FieldMediaItem from './MediaItem.vue';
import FieldSelect from './Select.vue';
import FieldText from './Text.vue';
import FieldTextarea from './Textarea.vue';
import FieldWysiwyg from './Wysiwyg.vue';

export default {
    //	Again load all field components
    //	This doesn't work without doing this
    components: {
        'layout-boolean': FieldBoolean,
        'layout-date': FieldDate,
        'layout-media-item': FieldMediaItem,
        'layout-select': FieldSelect,
        'layout-text': FieldText,
        'layout-textarea': FieldTextarea,
        'layout-wysiwyg': FieldWysiwyg,
    },

    //	The props to accept
    props: ['settings', 'data'],

    //	Return data, content is default array
    data() {
        return { content: [] };
    },

    mounted() {
        //	If prop with data is filled, connent this with the content data property
        //	We will work with the data instead of the prop in this component
        if (this.data) {
            this.content = this.data;
        }
    },

    computed: {
        //	Get the amount of items
        repeaterLength() {
            if (!this.data) {
                return 1;
            }

            return this.data.length;
        },
    },

    methods: {
        generateUniqueId(key, type) {
            let id = (Math.random() * (key + 1)).toString(36).substr(2, 16);
            return type + key + id;
        },

        //	Pass the data for each field
        getDataForChild(index, id) {
            if (typeof this.content[index][id] === 'undefined') return null;

            return this.content[index][id];
        },

        //	Event for updating a single value
        updateChild(id, value, index) {
            this.content[index][id] = value;

            //	Emit to the parent component
            this.$emit('update', this.settings.id, this.content);
        },

        // 	Add item to the repeater
        addItem(index) {
            //	Fill the new item with all avaiable properties
            let item = {};
            for (let i in this.settings.children) {
                item[this.settings.children[i].id] = null;
            }

            //	If no current index defined, add the component at the bottom
            if (typeof index === 'undefined') {
                this.content.push(item);
                return;
            }

            //	Add the the item after the current index
            this.content.splice(index + 1, 0, item);
        },

        //	Delete item
        deleteItem(index) {
            //	Delete the component with the given index of the array
            this.content.splice(index, 1);

            this.$emit('update', this.settings.id, this.content);
        },

        //	Move item one position down
        moveItemDown(index) {
            if (typeof this.content[index + 1] == 'undefined') {
                return;
            }

            let item = this.content[index];

            this.content.splice(index, 1);
            this.content.splice(index + 1, 0, item);
        },

        //	Move item one position up
        moveItemUp(index) {
            if (typeof this.content[index - 1] == 'undefined') {
                return;
            }

            let item = this.content[index];

            this.content.splice(index, 1);
            this.content.splice(index - 1, 0, item);
        },
    },
};
</script>
