<script setup lang="ts">
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import type { Ref } from 'vue';
import type { ItemType } from '@/@types/model';
import type { ValidationErrors } from '@/@types/types';

const isShow: Ref<boolean> = ref(false);
const toggleStatus = (): void => { isShow.value = !isShow.value};

type Props = {
  item: ItemType;
  userName: string;
  errors: ValidationErrors;
}

const props = defineProps<Props>();

const form = useForm({
    disposal_date: new Date().toISOString().substr(0, 10) as string,
    disposal_person: props.userName as string,
    details: null as string | null,
});

// disposalsテーブルに保存する関数
const saveDisposal = (item: ItemType): void => {
  try {
    if (confirm('本当に廃棄しますか？')) {
      form.put(`/dispose_item/${item.id}`, {
        onSuccess: () => {
          toggleStatus();
        },
      });
    }
  } catch (e: any) {
    axios.post('/api/log-error', {
      error: e.toString(),
      component: 'DisposalModal.vue saveDisposal method',
    });
  }
};
</script>

<template>
  <div v-show="isShow" class="modal fixed inset-0 bg-gray-600 bg-opacity-50 flex items-end md:items-center md:justify-center z-50" id="modal-1" >
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div class="modal__container bg-white w-full md:w-2/3 lg:w-1/3 md:h-auto md:rounded-lg p-4 md:p-8 md:shadow-lg md:transform-none transform md:translate-y-0  transition-transform duration-500 ease-in-out" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
        <header class="modal__header">
          <h2 class="flex modal__title" id="modal-1-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
            </svg>
            <span class="text-sm lg:text-lg">廃棄</span>
          </h2>
          <button @click="toggleStatus" type="button" class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>
        <main class="modal__content" id="modal-1-content">
          <!-- フォームの開始 -->
          <form @submit.prevent="saveDisposal(item)">
            <div>
              <div class="p-2 w-full">
                  <label for="name" class="leading-7 text-xs md:text-base text-blue-900">
                      備品名
                  </label>
                  <div id="name" name="name" class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                      {{ item.name }}
                  </div>
              </div>
              <div class="p-2 w-full">
                <label for="disposalSchedule" class="leading-7 text-xs md:text-base text-blue-900">
                  廃棄予定日
                </label>
                <div id="disposalSchedule" name="disposalSchedule" class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                  {{ item.disposal && item.disposal.disposal_scheduled_date ? item.disposal.disposal_scheduled_date : '予定なし' }}  
                </div>
              </div>
              <div class="p-2 w-full">
                  <label for="disposal_date" class="leading-7 text-xs md:text-base text-blue-900">
                    廃棄実施日
                    <span class="ml-1 mr-2 bg-red-400 text-white text-xs py-1 px-2 rounded-md">必須</span>
                  </label>
                  <div class="relative">
                      <input type="date" id="disposal_date" name="disposal_date" v-model="form.disposal_date" class="md:mt-1 w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                  </div>
                  <div v-if="errors.disposal_date" class="font-medium text-xs md:text-base text-red-600">{{ errors.disposal_date }}</div>
              </div>
              <div class="p-2 w-full">
                <label for="disposal_person" class="leading-7 text-xs md:text-base text-blue-900">
                  廃棄実施者
                  <span class="ml-1 mr-2 bg-red-400 text-white text-xs py-1 px-2 rounded-md">必須</span>
                </label>
                <div>
                  <input type="text" id="disposal_person" name="disposal_person" v-model="form.disposal_person" class="md:mt-1 w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                  <div v-if="errors.disposal_person" class="font-medium text-xs md:text-base text-red-600">{{ errors.disposal_person }}</div>       
                </div>
              </div>
              <div class="p-2 w-full">
                <label for="details" class="leading-7 text-xs md:text-base text-blue-900">
                  詳細情報
                  <span class="ml-1 mr-2 bg-red-400 text-white text-xs py-1 px-2 rounded-md">必須</span>
                </label>
                <div>
                  <textarea id="disposalDetails" name="details" maxlength="500" v-model="form.details" class="md:mt-1 w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-xs md:text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                  <div v-if="errors.details" class="font-medium text-xs md:text-base text-red-600">{{ errors.details }}</div>
                </div>
              </div>
            </div>
            <div class="p-2 w-full">
              <button class="flex mx-auto text-white text-xs md:text-sm bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded">
                廃棄を実施する
              </button>
            </div>
          </form>
        </main>
      </div>
    </div>
  </div>
  <button @click="toggleStatus" type="button" id="disposalButton" data-micromodal-trigger="modal-1" href='javascript:;' class="flex mx-auto md:ml-4 text-xs md:text-sm text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded">
    廃棄する
  </button>
</template>