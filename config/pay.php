<?php

return [
    'alipay' => [
        'app_id'         => '2021000119600641',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtBJC5xK+w7XJkxADaMrS5rYMwvAHBr09nRqCI0Ux//YZNhoXlIlPyxtOHEwOvRDVI3U//IsRuH0V/fNJQUhn1TXv5+tFfcm01VaUtnemB9UeCB3ZfZkcXxfOpW1DQboiB9+Tss4zRUJIXOuyrLIXLvYcF+nVzjNVL1x7zAoIi1Fds/Ul3Bv6hc01y75lcWi9imhhLBC3YR10OoZZ+lpNMZkiE+PRfTis4cGQmaVVlP3+HA082yGmanAsghq7SqcBtYNiiaO73Atxr3JZC5iPb0KReRs+0G31wLdH3/aNPhOlc8tDI52jNXQgCF1IoLC2PvGK8Rp0L5rj6ELO1I1JRQIDAQAB',
        'private_key'    => 'MIIEpAIBAAKCAQEAlvKtjLNKRl6Zb5xauvv6aFlDXAqu3X7J0WP+EM6QX/G8qxsWvjvbK7gL8XOH/bVHrbZYp7o3WlptOnHBYOs2jEGS8p6zEXAbLo52JzsTk36IbZvxdYWjacR9K119ZGZZOeMWiIoGYn2G03H62t4n3xLWq+bz4BmDZYKpgeQg4gx41l6VLLSf5snacF8x2FeJkFEbyBGhE4OOHordWPxxSy7wINCpGana2yRZDEXyXzzWWuvhfRiyLPJu959od/Qe1Supp6i3ivXkXwXhKBRHWcHlQqZZnN7WCSL3n0SMQ++KK7fVj8EdjKu1QbouGDZ04j9d19NMP5UrOyn7dz9BYQIDAQABAoIBAH54ExpIeVmeFtYhXQ8+4R2edEZgmQPxH6x1J7/zIZmRUy7VAmgehFIL4M3Kq0zRHp4Xog3dglUMYGnQN2I5kDlRE4p9lrBsigmmyK2z3IH+SNX1CwgZEMIkI2865D1DV0ydzBpX8mOAon70B2ZrLWLESOI8HWZMWf+pytWOSlK5J4KnIelD+w54BjCOwAM3W4rOpvdnisLapLgXhsaAauk5BYPU8pL6IvpbhBBTsoKJwx/5HHaER+nzo4epHGRs+eXMy5WhJ9lFT1Mamv35QRSbudmp9uImklJWfQYWR22GTA/sgzKMLZID3jYsYJFeR3fnZv4BkYYviW/toE1x98ECgYEA+bDKizelfLt6dHOopCHpkwdSx0nMCtTG55crWYdf/xrEzv/1F8E9wucYzAL/DEj9csKoNhFW/M+6kVxiskaiPPC1wFZoNJlWuTAQiPk6giuwUvgfoWN9hdJIe34OZkbGK+pg9wgo3tgA344ZTgwQTQmd2csD8a+uUs2k3b8QyHkCgYEAmsMi5uGYPlyu36yQgcI8wQaOeWPd+NmZ7sH8Yva2ZO5rrICrbGIjxdbbaT/dIatjKgRhixg0nMwvzpjhNb6yTRL7Z3GKEzcomsqUi9L7/uF2xeHBvvXn4fFkLtrRvoZkTkRusV23b1Y8YoGh3DFlhDpgM+UCpAuNzEtgwjtK1ikCgYEAqOyUew9QpExqEjLVWwWeclw4Ap/IrWM6lh/NsipGJd7EW48LD+EqmZujSRKV3ofbADL1fm7IvQ0Emac5Fod252eqbs+GfF9pLqx341NJ5BsOlXNMYvFeUJTteK7Vqxgipj9RDo+0pt7X6GbIc+bxeE0TP+97YZ2LgHWkHmlPP3ECgYAVaxgzd9lIGC+jiRBexPD/jda9+hJIVGU3Y+V4FMjdYxnHv+75iwOEZ93pzQrflAafVAfj5i+x25hqMUJJ2+B1RkFA6bfAPQwDkDHKwCJb/fwgGsRjl0jGPlAtI9+PTK4pHtNq/Jtcb0TgJSyveq6gBCw4QmOBLJAtS+lSOD59MQKBgQCYhrLl1jqUiCwJZqUqBzhg9FA9sDJXN2XVyye/3cQl7N2F0UMSySeDS2xsqeoSV0o/Uv5ZZiwX0Yfeu+vTV2fXGKLKaJeOm0bYrKlFumYTgf8z1b27q3AFkN04KXtvXEYHsciIZuGd0UMvTDio7ZewZDhToJR3t82lABjyztc5yQ==',
        'log'            => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id'      => '',
        'mch_id'      => '',
        'key'         => '',
        'cert_client' => '',
        'cert_key'    => '',
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];
