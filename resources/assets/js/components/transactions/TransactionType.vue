<!--
  - TransactionType.vue
  - Copyright (c) 2019 james@firefly-iii.org
  -
  - This file is part of Firefly III (https://github.com/firefly-iii).
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program.  If not, see <https://www.gnu.org/licenses/>.
  -->

<template>
  <div class="form-group">
    <div class="col-sm-12">
      <label v-if="sentence !== ''" class="control-label text-info">
        {{ sentence }}
      </label>
    </div>
  </div>


</template>

<script>
export default {
  props: {
    source: String,
    destination: String,
    type: String
  },
  methods: {
    changeValue: function () {
      if (this.source && this.destination) {
        let transactionType = '';
        if (window.accountToTypes[this.source]) {
          if (window.accountToTypes[this.source][this.destination]) {
            transactionType = window.accountToTypes[this.source][this.destination];
          } else {
            console.warn('User selected an impossible destination.');
          }
        } else {
          console.warn('User selected an impossible source.');
        }
        if ('' !== transactionType) {
          this.transactionType = transactionType;
          this.sentence = this.$t('firefly.you_create_' + transactionType.toLowerCase());

          // Must also emit a change to set ALL sources and destinations to this particular type.
          this.$emit('act:limitSourceType', this.source);
          this.$emit('act:limitDestinationType', this.destination);
        }
      } else {
        this.sentence = '';
        this.transactionType = '';
      }
      // emit event how cool is that.
      this.$emit('set:transactionType', this.transactionType);
    }
  },
  data() {
    return {
      transactionType: this.type,
      sentence: ''

    }
  },
  watch: {
    source() {
      this.changeValue();
    },
    destination() {
      this.changeValue();
    }
  },
  name: "TransactionType"
}
</script>
