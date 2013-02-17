# Upgrade from 1.0 to 2.0

The build.number file in ``app/azure/build.number`` was removed
and is now located in ``app/config/azure_build_number.yml``. The format
has changed to:

    parameters:
        azure_build: 123

If you are using asset deployment, you have to copy the build number over
in the new format, to avoid reusing asset build directories.

