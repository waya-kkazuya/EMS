<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DisposalModal from '@/Components/DisposalModal.vue';
import EditHistoryModal from '@/Components/EditHistoryModal.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import InspectionModal  from '@/Components/InspectionModal.vue';

import type { ItemType, InspectionType } from '@/@types/model';
import type { ValidationErrors } from '@/@types/types';

defineProps<{
    item: ItemType,
    uncompleted_inspection: InspectionType | null,
    last_completed_inspection: InspectionType | null,
    userName: string,
    errors: ValidationErrors
}>();
</script>

<template>
  <Head title="備品詳細" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        備品詳細
      </h2>
    </template>

    <div class="py-2 md:py-4">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- ここから白い背景 -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">

            <section class="text-gray-600 body-font relative">
              <FlashMessage />
              <div class="container px-5 py-8 mx-auto">                                    
                <div class="md:w-full mx-auto">
                  <div class="-m-2">
                    <div class="mb-2 flex justify-end space-x-1">
                      <EditHistoryModal :item="item" :isTableView="false" />
                      <Link as="button" :href="route('items.edit', { item: item.id })" id="editItem" class="flex text-white bg-indigo-500 border-0 py-2 px-4 focus:outline-none hover:bg-indigo-600 rounded text-xs md:text-sm">
                        編集する
                      </Link>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4">
                      <div class="col-span-1">
                        <div class="p-4 border bordr-4 mb-4">
                          <div class="p-2 w-full">
                            <label for="fileName" class="leading-7 text-xs md:text-base text-blue-900">
                              画像
                            </label>    
                            <div v-if="item.image_path1" class="flex justify-center border border-gray-300 shadow-sm">
                              <img :src="item.image_path1" alt="画像" class="w-68 mt-4">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-span-2">
                        <div class="p-4 border bordr-4 mb-4">
                          <div class="p-2 w-full">
                            <label for="name" class="leading-7 text-xs md:text-base text-blue-900">
                              備品名
                            </label>
                            <div id="name" name="name" class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                              {{ item.name }}
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <label for="categoryId" class="leading-7 text-xs md:text-base text-blue-900">
                              カテゴリ
                            </label><br>
                            <div class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                              {{ item.category.name }}
                            </div>
                          </div>  
                        </div>
                          
                        <div class="p-4 border bordr-4 mb-4">
                          <div class="pl-2 w-full">
                            <label for="stock" class="leading-7 text-xs md:text-base text-blue-900">
                              在庫数
                            </label><br>
                            <div class="flex items-center">
                              <div id="stock" name="stock" class="w-1/4 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                {{ item.stock }}
                              </div>
                              <div class="w-1/6 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                {{ item.unit.name }}
                              </div>
                            </div>
                          </div>
                          <div v-if="item.category_id === 1" class="mt-4 pl-2 w-full">
                            <label for="minimumStock" class="leading-7 text-xs md:text-base text-blue-900">
                              通知在庫数
                            </label><br>
                            <div class="flex items-center">
                              <div id="minimumStock" name="minimumStock" class="w-1/4 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                {{ item.minimum_stock }}
                              </div>
                              <div class="w-1/6 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                {{ item.unit.name }}
                              </div>
                            </div>
                          </div>
                          <div v-show="item.category_id === 1" class="mt-4 pl-2 w-full">
                            <div class="flex items-center">
                              <svg v-if="item.notification" class="w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                              <svg v-else class="w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect></svg>
                              <label for="notification" class="ml-1 text-xs md:text-base">在庫数が通知在庫数以下になったら通知する</label>
                            </div>
                          </div>
                        </div>

                        <div class="p-4 border bordr-4 mb-4">
                          <div class="p-2 w-full">
                            <label for="usageStatusId" class="leading-7 text-xs md:text-base text-blue-900">
                              利用状況
                            </label>
                            <div name="usageStatusId" id="usageStatusId" class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                              {{ item.usage_status.name }}
                            </div>
                          </div>

                          <div class="p-2 w-full">
                            <label for="endUser" class="leading-7 text-sm text-blue-900">
                              使用者
                            </label>
                            <div id="endUser" name="endUser" class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out min-h-[2em]">
                              {{ item.end_user || 'なし' }}
                            </div>
                          </div>

                          <div class="p-2 w-full">
                            <label for="locationOfUseId" class="leading-7 text-xs md:text-base text-blue-900">
                              利用場所
                            </label>
                            <div name="locationOfUseId" id="locationOfUseId" class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                              {{ item.location_of_use.name }}
                            </div>
                          </div>

                          <div class="p-2 w-full">
                            <label for="storageLocationId" class="leading-7 text-xs md:text-base text-blue-900">
                              保管場所
                            </label>
                            <div name="storageLocationId" id="storageLocationId" class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                              {{ item.storage_location.name }}
                            </div>
                          </div>
                        </div>

                        <div class="p-4 border bordr-4 mb-4">
                          <div class="p-2 w-full">
                            <label for="acquisitionMethodId" class="leading-7 text-xs md:text-base text-blue-900">
                              取得区分
                            </label>
                            <div name="acquisitionMethodId" id="acquisitionMethodId" class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                              {{ item.acquisition_method.name }}
                            </div>
                          </div>

                          <div class="p-2 w-full">
                            <label for="acquisitionSource" class="leading-7 text-xs md:text-base text-blue-900">
                              取得先
                            </label>
                            <div id="acquisitionSource" name="acquisitionSource" class="w-full min-h-[2em] bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                              {{ item.acquisition_source || '' }}
                            </div>
                          </div>

                          <div class="p-2 w-full">
                            <label for="price" class="leading-7 text-xs md:text-base text-blue-900">
                              取得価額
                            </label>
                            <div id="price" name="price" class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                              {{ item.price }}
                            </div>
                          </div>

                          <div class="p-2 w-full">
                            <label for="dateOfAcquisition" class="leading-7 text-xs md:text-base text-blue-900">
                              取得年月日
                            </label>
                            <div id="dateOfAcquisition" name="dateOfAcquisition" class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                              {{ item.date_of_acquisition ?? '' }}
                            </div>
                          </div>
                        </div>

                        <div class="p-4 border bordr-4 mb-4">
                          <div class="p-2 w-full">
                            <label for="manufacturer" class="leading-7 text-xs md:text-base text-blue-900">メーカー</label>
                            <div id="manufacturer" name="manufacturer" class="w-full min-h-[2em] bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                              {{ item.manufacturer ?? 'なし' }}
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <label for="productNumber" class="leading-7 text-xs md:text-base text-blue-900">製品番号</label>
                            <div id="productNumber" name="productNumber" class="w-full min-h-[2em] bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                              {{ item.product_number ?? 'なし' }}    
                            </div>
                          </div>
                          <div class="p-2 w-full">
                            <label for="remarks" class="leading-7 text-xs md:text-base text-blue-900">備考</label>
                            <div id="remarks" name="remarks" class="overflow-y-auto max-h-32 whitespace-pre-wrap w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-xs md:text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out">
                              {{ item.remarks }}    
                            </div>
                          </div>
                        </div>

                        <div class="p-4 border bordr-4 mb-2">
                          <div class="p-2 w-full">
                            <label for="inspection_scheduled_date" class="leading-7 text-xs md:text-base text-blue-900">
                              点検予定日
                            </label>
                            <div class="flex items-center">
                              <div id="inspection_scheduled_date" name="inspection_scheduled_date" class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                {{ uncompleted_inspection ? uncompleted_inspection.inspection_scheduled_date : 'なし' }}
                              </div>
                              <div class="w-full">
                                <InspectionModal :item="item" :userName="userName" :uncompleted_inspection="uncompleted_inspection" :errors="errors" />
                              </div>
                            </div>
                            <div class="mt-4 leading-7 text-xs md:text-base">前回の点検日: {{ last_completed_inspection ? last_completed_inspection.inspection_date : 'なし' }}</div>
                          </div>

                          <div class="p-2 w-full">
                            <label for="disposalSchedule" class="leading-7 text-xs md:text-base text-blue-900">
                              廃棄予定日
                            </label>
                            <div class="flex items-center">
                              <div type="date" id="disposalSchedule" name="disposalSchedule" class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-xs md:text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                {{ item.disposal && item.disposal.disposal_scheduled_date ? item.disposal.disposal_scheduled_date : 'なし' }}
                              </div>
                              <div class="w-full">
                                <DisposalModal :item="item" :userName="userName" :errors="errors" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="p-2 w-full">
                          <Link as="button" :href="route('items.edit', { item: item.id })" id="editItem" class="flex mx-auto text-xs md:text-sm text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded">
                            編集する
                          </Link>
                        </div>
                      </div>
                    </div>                      
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>