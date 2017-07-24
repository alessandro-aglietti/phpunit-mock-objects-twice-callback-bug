# phpunit-mock-objects-twice-callback-issue


same problem descripted here


https://github.com/sebastianbergmann/phpunit-mock-objects/issues/181


but when testing mocks that are using stream things going badly then 'called twice'


UploadSimulator had 2 methods
- upload (string, string, stream)
- upload_simplified(string)

upload_simulator generate resources name and open the stream, then pass all to upload


TestCase simulate two consecutive uploads using
- withConsecutive
- callback

as the Travis log show the callbacks are invoked more tha expected and in the `test_upload_simplified_two_consecutive_uploads` is used the FIRST callback instead of the SECOND callback