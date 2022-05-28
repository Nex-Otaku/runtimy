import {defineStore} from 'pinia'
import {api} from 'src/boot/axios'

const transport_options = [
  {
    label: 'Пешком',
    value: 'feet'
  },
  {
    label: 'Легковой',
    value: 'passenger'
  },
  {
    label: 'Грузовой',
    value: 'cargo'
  },
];

const size_options = [
  {
    label: 'Мелкий',
    value: 'small'
  },
  {
    label: 'Средний',
    value: 'medium'
  },
  {
    label: 'Крупный',
    value: 'large'
  },
  {
    label: 'Очень крупный',
    value: 'extra-large'
  },
];

const weight_options = [
  {
    label: 'До 1 кг',
    value: '1kg'
  },
  {
    label: 'До 5 кг',
    value: '5kg'
  },
  {
    label: 'До 10 кг',
    value: '10kg'
  },
];

const getOptionByCode = (options, code) => {
  for (const option of options) {
    if (option.value === code) {
      return option;
    }
  }

  throw new Error('Неизвестная опция: ' + code);
}

const getTransportTypeOption = (transportType) => {
  return getOptionByCode(transport_options, transportType);
}

const getSizeTypeOption = (sizeType) => {
  return getOptionByCode(size_options, sizeType);
}

const getWeightTypeOption = (weightType) => {
  return getOptionByCode(weight_options, weightType);
}

const getPlaceTitle = (sortIndex) => {
  if (sortIndex === 1) {
    return 'Откуда';
  }

  if (sortIndex === 2) {
    return 'Куда';
  }

  return 'Место #' + sortIndex;
}

export const useOrderForm = defineStore(
  'order-form',
  {
    state: () => ({
      isLoaded: false,
      orderNumber: null,
      transport_type: getTransportTypeOption('feet'),
      size_type: getSizeTypeOption('small'),
      weight_type: getWeightTypeOption('1kg'),
      places: [
        {
          'title': 'Откуда',
          'sort_index': 1,
          'street_address': '',
          'phone_number': '',
          'courier_comment': ''
        },
        {
          'title': 'Куда',
          'sort_index': 2,
          'street_address': '',
          'phone_number': '',
          'courier_comment': ''
        },
      ],
      description: '',
      price_of_package: ''
    }),

    getters: {
      transport_options: () => {
        return transport_options;
      },
      size_options: () => {
        return size_options;
      },
      weight_options: () => {
        return weight_options;
      },
      form: (state) => {
        const resultPlaces = [];

        for (const place of state.places) {
          resultPlaces.push({
            sort_index: place.sort_index,
            street_address: place.street_address,
            phone_number: place.phone_number,
            courier_comment: place.courier_comment
          });
        }

        return {
          transport_type: state.transport_type.value,
          size_type: state.size_type.value,
          weight_type: state.weight_type.value,
          description: state.description,
          price_of_package: state.price_of_package,
          places: resultPlaces
        }
      }
    },

    actions: {
      async createOrder() {
        return api.post('/api/new-order', this.form)
      },
      async loadOrder(orderId) {
        return api.get('/api/load-order/' + orderId)
          .then(response => {
            if (response.data.result !== 'success') {
              console.error(response.data.message);
            }

            const orderFields = response.data.data;
            const places = [];

            for (const place of orderFields.places) {
              places.push({
                title: getPlaceTitle(place.sort_index),
                sort_index: place.sort_index,
                street_address: place.street_address,
                phone_number: place.phone_number,
                courier_comment: place.courier_comment,
              })
            }

            this.$patch({
              isLoaded: true,
              orderNumber: orderFields.order_number,
              transport_type: getTransportTypeOption(orderFields.transport_type),
              size_type: getSizeTypeOption(orderFields.size_type),
              weight_type: getWeightTypeOption(orderFields.weight_type),
              price_of_package: orderFields.price_of_package,
              description: orderFields.description,
              places: places,
            })
          });
      },
      async updateOrder(orderId) {
        return api.post('/api/update-order/' + orderId, this.form);
      },
      addPlace() {
        const places = this.places;
        const placesCount = this.places.length;
        const newIndex = placesCount + 1;

        places.push({
          'title': 'Место #' + newIndex,
          'sort_index': newIndex,
          'street_address': '',
          'phone_number': '',
          'courier_comment': ''
        })
      }
    }
  },
)
