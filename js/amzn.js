Access Key ID:
AKIAIOEYBLQQ7WPZB5BA
Secret Access Key:
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

    var baseURL = "http://webservices.amazon.com/onca/xml";
    var service = "?Service=AWSECommerceService";
    var operation = "&Operation=ItemLookup";
    var responseGroup = "&ResponseGroup=Images";
    var idType = "&IdType=ASIN&";
    var itemId = "&ItemId= B004HO6I4M";
    var accessKey = "&AWSAccessKeyId=AKIAIWMSSH2GN4ANMXHQ";
    var associateTag = "&AssociateTag=";
    var timestamp;
    //var signature = "&Signature=" + getSignatureKey();

    var d = new Date,
        dformat = [ (d.getMonth()+1).padLeft(),
                    d.getDate().padLeft(),
                    d.getFullYear()].join('-')+
                    'T'+
                  [ d.getHours().padLeft(),
                    d.getMinutes().padLeft(),
                    d.getSeconds().padLeft('')].join('%3A') + 'Z';
    timestamp = dformat;
    var unsignedStr = baseURL + service + operation + responseGroup + idType + itemId + accessKey;
    alert(timestamp);
  
http://webservices.amazon.com/onca/xml?Service=AWSECommerceService&Operation=ItemLookup&ResponseGroup=Images&IdType=ASIN&&ItemId=B004HO6I4M&AWSAccessKeyId=AKIAIWMSSH2GN4ANMXHQ&Timestamp=2015-01-01T17%3A00%3A00Z&Signature=8934d56f2ee819f6ee9080e08a5f11256ebeaf0e24a10cb1ec4e7323d167a6ac

GET
webservices.amazon.com
/onca/xml
AWSAccessKeyId=AKIAIOEYBLQQ7WPZB5BA&ItemId=B004HO6I4M&Operation=I
temLookup&ResponseGroup=ItemAttributes%2COffers%2CImages%2CReview
API Version 2013-08-01
61
Product Advertising API Developer Guide
Request Authentications&Service=AWSECommerceService&Timestamp=2009-03-22T17%3A00%3A00Z&
Version=2009-01-06

1ba9072706a2c64190a65976b4b6f9093dcf8cb6b3bc341cc3884bc2aa00ce13

\\
http://webservices.amazon.com/onca/xml?AWSAccessKeyId=AKIAIOEYBLQQ7WPZB5BA&ItemId=B004HO6I4M&Operation=ItemLookup&ResponseGroup=ItemAttributes%2COffers%2CImages%2CReviews&Service=AWSECommerceService&2009-03-22T17%3A00%3A00Z&Version=2015-01-06&Signature=8934d56f2ee819f6ee9080e08a5f11256ebeaf0e24a10cb1ec4e7323d167a6ac




Group=Images
  IdType=ASIN&
  ItemId= B004HO6I4M
  AWSAccessKeyId=AKIAIOEYBLQQ7WPZB5BA
  &imestamp=2009-01-01T12%3A00%3A00Z
  Signature=getSignatureKey()
  
function getSignatureKey(key, dateStamp, regionName, serviceName) {

   var kDate= Crypto.HMAC(Crypto.SHA256, dateStamp, "AWS4" + key, { asBytes: true})
   var kRegion= Crypto.HMAC(Crypto.SHA256, regionName, kDate, { asBytes: true });
   var kService=Crypto.HMAC(Crypto.SHA256, serviceName, kRegion, { asBytes: true });
   var kSigning= Crypto.HMAC(Crypto.SHA256, "aws4_request", kService, { asBytes: true });

   return kSigning;
}

