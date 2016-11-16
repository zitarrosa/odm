```
curl -XPUT 'http://localhost:9900/testing/' -d '{
    "settings" : {
        "index" : {
            "number_of_shards" : 1, 
            "number_of_replicas" : 1 
        }
    }
}'

curl -XPUT 'http://localhost:9900/testing/_mapping/students' -d '{
    "properties": {
        "first_name": {
          "type": "string"
        },
        "last_name": {
          "type": "string"
        },
        "approved_courses": {
          "type": "string"
        }
    }
}'
```