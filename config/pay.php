<?php

return [
    'alipay' => [
        'app_id'         => '2021000119600641',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtBJC5xK+w7XJkxADaMrS5rYMwvAHBr09nRqCI0Ux//YZNhoXlIlPyxtOHEwOvRDVI3U//IsRuH0V/fNJQUhn1TXv5+tFfcm01VaUtnemB9UeCB3ZfZkcXxfOpW1DQboiB9+Tss4zRUJIXOuyrLIXLvYcF+nVzjNVL1x7zAoIi1Fds/Ul3Bv6hc01y75lcWi9imhhLBC3YR10OoZZ+lpNMZkiE+PRfTis4cGQmaVVlP3+HA082yGmanAsghq7SqcBtYNiiaO73Atxr3JZC5iPb0KReRs+0G31wLdH3/aNPhOlc8tDI52jNXQgCF1IoLC2PvGK8Rp0L5rj6ELO1I1JRQIDAQAB',
        'private_key'    => 'MIIEowIBAAKCAQEApnm/O/5binT7FRgazc3pU5rDPKFULB+9pVYkmG9W1f4A8IOiuZ+4wtZJ0rY7AuIkJtaxq7og8KL4hedllL3YAo1szninc9nFxrjdorZP0WrPBy4FcmfAR7j8jYeo+9FiXI3HmF4CoQakKd/seTlSw14YFwrcaHVYFffOQgGNuhDEoA9jn3NqmsMYUUPOpuHsHFDhBAkc8RotyTkfjsGUQJftPYUI2tsGK3cOyqHycvV80K2EPOgetD7eINRGqsqkTOkSTLmXCe0m5Qvg/8KvY05ZiRTE52Cm4c9XXq9t9PUXsUXMLrKOTF4c8QHfJ38XtxbwZ0Fpi8yVDn8XcXJW/wIDAQABAoIBADjMYQq/Bcx/jQrtZnfBQVJdAy+0e1tymOnIMkQv+JiaSQfGYcajUA5pvlY+BnQ9Y8g8h/HnV0XC1Lga8vWEsCZuyijH0ZodOvVKLZROT31Ly8bPVNxruZa+7qv7FRSVo9GgLfE2Yf8+nNQMqknR3QL7H4z9D6Y0IxGbsquijBBnCuTmwESH7Tg3gAaHEH90S+S2KR7uQhqgVuImkEiLgwwFifadA0PwAZluNmbf9eXon0KX0dtWyPSdm61gGFgLml0JTKVxesgtGrbTu4j4fATh8rranV7bZnhZwPriM8hdQECpi0CtF5BQMINL6CFZ1tl5t/15UVnNfn4tj56t2sECgYEA3wC9fnK6dshG+DnQ/Uge/MPs3z2Xd8+7urMZKBgUJGislIaMmsHv9PiGmxWMIWY4t+oOfUtgkPGiwaojxZveTlbh5jLIt3Yqwwvu5N1fbnfffBal/gGOStQ4Amq+4UxFf24LvdQZwqDPzVO+aK601JcIIJxnGj3SJ71wUi8RBBMCgYEAvxvGV1o91SMfnVIT8IdIKcT+0owD5+QQ2o6ZBgl0yrVKcTbAz+gnXO3hhj4IwYDkpOX9l0GqT0Fjx1cjenZJF04BkN34MrlQO6+MVXlyIEBcu/X9Sq6VNOZ6joLg5wfuDbUllhFpKjkRjZUPrIrnHIboyL/BUY8FmQ49mW4BxuUCgYEAwd9S3uu/UCUuDf+1wX1B814iQLK7TF49R0MNS94jJMQbxcRz2NdiVgb6fzlsal0EUmAZ2LP/cAOjC9tu3g10Z4cBPbBI77a71nY5ap9KqC9vC+JkzT9tBYEacH99E0HL6c0ySAZtntdP0aJMl5XLgEcNucw/loDIg+QeElT45iECgYAHwPW8J5IoqqMmu+TJNoPsBe0c18d0yiEQ7QYyY4HUk6RwkXsjNFUchR5IVw3949/i/N3cHDLvwkRtGxD6cAApLOYNXaQrUq95rHayJRpHSrU4fxOGyyyMl+lTDOZZGU5EShw7SXuN1gDrUKfxJTWZR7P8KSOLzpS4kAZZykfSLQKBgHXwy1n1eBkZjqbCDDVYJJYAsvFWBaw0ChRtVSKeQeEFDD0KUheopKJkBcE56jl2CKbNlLgriTd3o3fOtYuvCVGgihVvD1hNKR/DP9aFtuM+pCMJpNH491n0iZ0dGQnwrZphYxcHeThpgthbsEqF1NoRj2Lh/R49IJ6wVZcBuuNX',
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
