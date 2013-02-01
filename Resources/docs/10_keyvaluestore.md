---
layout: default
title: Doctrine KeyValueStore Support
---

# Doctrine KeyValueStore Support

The Doctrine KeyValueStore project has support for the
Windows Azure Table Storage system. The distribution
bundle can connect both libraries and offers them
as a service ``windows_azure_distribution.key_value_store.entity_manager``.

The configuration is really simple:

    windows_azure_distribution:
        services:
            table:
                test: DefaultEndpointsProtocol=[http|https];AccountName=[yourAccount];AccountKey=[yourKey]
        key_value_store:
            connection_name: test

You can read more about Doctrine KeyValueStore
on their [documentation page](https://doctrine-keyvaluestore.readthedocs.org/en/latest/).
