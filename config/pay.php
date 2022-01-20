<?php

return [
    'alipay' => [
        'app_id'         => '2021000119600641',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtBJC5xK+w7XJkxADaMrS5rYMwvAHBr09nRqCI0Ux//YZNhoXlIlPyxtOHEwOvRDVI3U//IsRuH0V/fNJQUhn1TXv5+tFfcm01VaUtnemB9UeCB3ZfZkcXxfOpW1DQboiB9+Tss4zRUJIXOuyrLIXLvYcF+nVzjNVL1x7zAoIi1Fds/Ul3Bv6hc01y75lcWi9imhhLBC3YR10OoZZ+lpNMZkiE+PRfTis4cGQmaVVlP3+HA082yGmanAsghq7SqcBtYNiiaO73Atxr3JZC5iPb0KReRs+0G31wLdH3/aNPhOlc8tDI52jNXQgCF1IoLC2PvGK8Rp0L5rj6ELO1I1JRQIDAQAB',
        'private_key'    => 'MIIEogIBAAKCAQEAsoiA7Z9tLAFAqvO4Ehf7mL9OORNYiEBqwLirX4oqSaXar6cnJqn9hArCrQ+3vK3+JILJXPUYk5n2X5EZAcHMLB/MfBWaAoe0uAzR4tvjY3ndwdDf6+0pJByCcs2BAODKYk48rxyIFAbVJmSMNQb97z40zNMKzv8+Wvu1gaZsAvObtHg7SEF6c8bZYAG6rq/KSeOFK81Y62Vl2i2MGMuucOzqbIP8kDr0YS+vaOZPGSzlx+KlR2Hr37bixfWtwrzd5iu6l338ymq20m+oJxRGQqsmvPBmDjdFBCGznuKdB/l+tSDxk1pLYIXxLqakhZY3tM+BmEvS7jj6cdpYefAonQIDAQABAoIBAAq6/5zIn0Qs0xkzStXzkRCuNuJhUknmLURmFQ7J/B6vFETIdCbzwqQEDY1fS3jQsFy7a6TlqlN8xn0Z+HnNjxr70kuG9NnAFFGXm6nZ84HQGk8C4eKJP5bHC+Qgi+dkm2VrNGSF/3vMqVUEaBTr1aligMPqz1R/rNzFEjS3rvhCWF6kj+3t8a4rAsM/cuiCHpoR22xT3hJP3sn4JmCNjh1t/xxxVuk5baiCMH41MwKLViOOgVtyosBh6ON9R+rYVWwxgmff+kLOMJRYp6Qm2YnUdpSfyds/I8kY0AMQbZWIdzpVyBYW4eLb3jm8Y8W0dFoXOVLKUITuCuoTOdOgNJUCgYEA2UjayFenNpbTFYwTFDTcCWS5xe7z2/ETacB3MaCIOGdIBdQVAvPXOsPgj3BviBa4RJO16P0AYlOsySpLScm81dNpiRJiuikPkHoBkvxPHHq0Z10VnnRmP6srSZ7bMVxGU5ikaY60PNru3xaUhDgD+AURGwr464UQ4WZoG22DjgcCgYEA0lgOoFYxIoPvOFcSD3B3dX5Al4QgPNHmSbXTL9rcBE3gpk4vMh3UbWJOmtfLHyd6XphIwJVAUFqJyjpc5QltMv+GjsHZJ82V8RU2xqudmo+/FqbdrE1jHmsvqgY9UjJbtLXdZ9sfEl+Fc93MfNgeGyBFXDewZ/KC5on5jm1S6zsCgYBQ4jnvEhIA1CxHfRktEHBIXuJ4t/a3DQq49xhntOaAeJHq+YPI0ZRxH7FcM/KQqkR14rZ4wJWABL3xNHlKQnq07jn++IUf32EX2xLy8FFSvKqjP5dooNPfJ4y5nxThTwQu4kdxs9mMPcNl8DXg8ikoDlrLwk3e/m3GOiTO8PljyQKBgC2MxB4UjC9dQsCAmnidckEPRg2JnzGMgsITjdgfHOYRt0RMSgwgSZAmab8+W84zTlyNvbN2nqbH9G7GlLdEg1E35HulntDvxDigz2vWXhrKZicRm49kcsJzk67OCxlxrkpD195VpTTlWpDsxwuCAYL+SgbeSGU6Lv6dgl1Fb19hAoGARlpNDhst9CkQaN+zHvA2asTavJbL1a2mLDL434XBEa3HVGcMC4/Q4g8prHMhMDEdZXGPNt5OFw5fjAx2sd5xqcTMrxHSXmRYLI2JilXrKdDZ85B6m5MR9KtCGVY5tyiWKxibKU4GUemLHQC7R8inD4VEHHJelQhFk8J4JjPNXWE=',
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
