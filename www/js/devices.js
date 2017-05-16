define(['ionic', 'app'], function() {
    'use strict';

    var service = angular.module('main.devices', ['ionic', 'main']);
 
    service.factory('downloadInstallService', function() {
        return {
            download: function(url, success, failure) {
                if(DownloadInstall && url){
                    DownloadInstall.download(url, success, failure);
                }
            }
        };
    });
    
    service.factory('appInfoService', function() {
        return {
            info: function(success, failure) {
                if(navigator.appInfo){
                    navigator.appInfo.getVersion(function(version) {
                        if(version && "Unknown" != version){
                            success(version);
                        }else{
                            failure();    
                        }
                    });
                }else{
                    failure(); 
                }
            }
        };
    });
    
    service.factory('geoService', ['utils', 'geoReq', function(utils, geoReq) {
        var getPointByIp = function(onSuccess, onError){
            var conf = utils.confFactory(['geoip'], {params: {output: 'json', coor : 'bd09ll'}});
            geoReq(conf).success(function (data) {
                if(data && data.status == 0){
                    onSuccess({latitude:data.content.point.y, longitude:data.content.point.x});
                }else{
                    onError();
                }
            }).error(onError);
        };
        
        var geoConv = function(position, onSuccess, onError){
            var conf = utils.confFactory(['geoconv'], {params: {output: 'json', from: 1, coords: position.longitude.toString()+','+position.latitude.toString()}});
            geoReq(conf).success(function (data) {
                if(data && data.status == 0){
                    onSuccess({latitude:data.result[0].y, longitude:data.result[0].x});
                }else{
                    onError();
                }
            }).error(onError);
        };
        
        var getPoint = function(onSuccess, onError){
            if(navigator && navigator.geolocation){
                navigator.geolocation.getCurrentPosition(function(position) {
                    geoConv({latitude:position.coords.latitude, longitude:position.coords.longitude}, onSuccess, onError);
                }, onError);
            }else {
                onError();
            }
        };
        var getLocalData = function(position, onSuccess, onError, pois){
            if(!pois){
                pois = 0;    
            }
            var poisCache = utils.get('pois') || {};
            var cacheKey = 'local_'+position.longitude.toString()+'_'+position.latitude.toString();
            var cache = poisCache[cacheKey] || {};
            if( cache.data && cache.addtime && cache.expires ){  
                if((cache.addtime + cache.expires) > new Date().getTime() ){       //缓存未过期
                    onSuccess(cache.data);
                    return ;
                }
            }
            //coordtype: 坐标的类型，目前支持的坐标类型包括：bd09ll（百度经纬度坐标）、gcj02ll（国测局经纬度坐标）、wgs84ll（ GPS经纬度） 
            var conf = utils.confFactory(['geocoder'], {params: {pois: pois, output: 'json', coordtype: 'bd09ll', location: position.latitude.toString()+','+position.longitude.toString()}});
            geoReq(conf).success(function (data) {
                if(data && data.status == 0){
                    var tmpCache = {};
                    var i = 0;
                    for(var k in poisCache){
                        if((poisCache[k].addtime + poisCache[k].expires) > new Date().getTime() ){       //缓存未过期
                            tmpCache[k] = poisCache[k];
                        }
                    }
                    tmpCache[cacheKey] = {};
                    tmpCache[cacheKey].data = data.result;
                    tmpCache[cacheKey].addtime = new Date().getTime();            //请求时间
                    tmpCache[cacheKey].expires = 3600000;                         //缓存TTL
                    utils.set('pois', tmpCache);                                  //缓存
                    onSuccess(data.result);
                }else{
                    onError();
                }
            }).error(onError);
        };
                
        return {
            getPoint: getPoint,
            geoConv: geoConv,
            getPointByIp: getPointByIp,
            getLocalData: getLocalData,
            getLocal: function(onSuccess, onError, pois){
                getPoint(function(point){
                    getLocalData(point, onSuccess, onError, pois);
                },onError);
            },
            /* 
            $scope.getDistance = function(){
                var pointA = {"lng":119.328249,"lat":26.124692};
                var pointB = {"lng":119.328667,"lat":26.124278};
                var dis = geoService.getDistance(pointA, pointB);
                alert('dis:'+dis);
            };
            */
            getDistance: function (pointA, pointB){
                var EARTH_RADIUS = 6378137.0; //单位M 
                var PI = Math.PI; 
                var getRad = function (d){ 
                    return d*PI/180.0; 
                };
                var radLat1 = getRad(pointA.lat); 
                var radLat2 = getRad(pointB.lat); 
                
                var a = radLat1 - radLat2; 
                var b = getRad(pointA.lng) - getRad(pointB.lng); 
                
                var s = 2*Math.asin(Math.sqrt(Math.pow(Math.sin(a/2),2) + Math.cos(radLat1)*Math.cos(radLat2)*Math.pow(Math.sin(b/2),2))); 
                s = s*EARTH_RADIUS; 
                s = Math.round(s*10000)/10000.0; 
                
                return s; 
            }
        };
    }]);
    
     service.factory('avatarService',['config', 'utils', function(config, utils) {
        return {
            modifyAvatar: function(source, auth, success, failure) {
                var pictureSource, destinationType;
                pictureSource   = navigator.camera.PictureSourceType;
                destinationType = navigator.camera.DestinationType;
                if (source) {
                    getLibrary();
                } else {
                    getCamera();
                }

                function getCamera() {
                    navigator.camera.getPicture(onSuccessPic, failure,
                        {
                            quality: 70,
                            destinationType : destinationType.FILE_URI,
                            sourceType : pictureSource.CAMERA,
                            allowEdit: true,
                            targetHeight: 200,
                            targetWidth: 200
                        }
                    );
                }

                function getLibrary() {
                    navigator.camera.getPicture(onSuccessPic, failure,
                        {
                            quality: 70,
                            destinationType: destinationType.FILE_URI,
                            sourceType: pictureSource.PHOTOLIBRARY,
                            targetWidth : 200,
                            targetHeight : 200
                        }
                    );
                }
                
                function onSuccessPic(imageURI) {
                    var options = new FileUploadOptions();
                    options.fileKey = "filedata";
                    //options.fileName = imageURI.substr(imageURI.lastIndexOf('/') + 1);
                    options.fileName = utils.getProfile('userid')+'_'+Math.ceil(Math.random()*1000000)+".jpg";
                    options.mimeType = "image/jpeg";
                    options.headers = {'auth': auth};
                    options.httpMethod = 'POST';
                    options.params = {};
                    var ft = new FileTransfer();
                    ft.upload(imageURI, utils.getApiServer() + config.resConfig.uploadAvatar.url, success, failure, options);
                }
            }
        };
     }]);

    service.factory('fileService', function() {
        var writeList = {};
        var cacheDir = 'louxiayoua';
        var launchDir = 'launch';
        var imageFormat = {
            'large_image': 'Default-568h@2x~iphone.png',
            'image': 'Default@2x~iphone.png',
            'mini_image': 'Default~iphone.png'
        };
        return {
            init: function(dirName, fileName, success, failure) {
                window.requestFileSystem(LocalFileSystem.PERSISTENT, 0, onSuccess, failure);
                // console.error(dirName);
                function onSuccess(fileSystem) {
                    // console.error(JSON.stringify(fileSystem.root));
                    fileSystem.root.getDirectory(dirName, {create: true},
                        function(dataDir) {
                            // console.error(JSON.stringify(dataDir),'dataDir');
                            dataDir.getFile(fileName, {}, getFileEntry, failure);
                        }, failure);
                }

                function getFileEntry(fileEntry) {
                    // console.error(fileName, 'success');
                    success(fileEntry);
                }

            },
            save: function(url, callback) {
                window.requestFileSystem(LocalFileSystem.PERSISTENT, 0, success, null);
                function success(fileSystem) {
                    var fileTransfer = new FileTransfer();
                    var uri = encodeURI(url);
                    var hash =  fileSystem.root.nativeURL + "louxiayoua/";
                    var end = "";
                    if (uri.indexOf('_') == -1) {
                        end = uri.substring(uri.lastIndexOf('/') + 1);    
                    } else {
                        end = (uri.substring(uri.lastIndexOf('/') + 1, uri.indexOf('_')) + uri.substring(uri.lastIndexOf('.')));
                    }
                    hash += end;
                    //writeList.push(end);
                    writeList[end] = 1;
                    fileTransfer.download(
                        uri,
                        hash,
                        function(entry) {
                            callback({success: true, message: 'success...'});
                            delete writeList[end];
                        },
                        function(error) {
                            // console.error(JSON.stringify(error));
                            callback({success: false, message: 'failure...'});
                        },
                        false
                    );
                }
            },
            read: function(url, callback, type) {
                var fileName = "";
                if (url.indexOf('_') == -1) {
                    fileName = url.substring(url.lastIndexOf('/') + 1);    
                } else {
                    fileName = (url.substring(url.lastIndexOf('/') + 1, url.indexOf('_')) + url.substring(url.lastIndexOf('.')));
                }
                // console.error(fileName);

                if (writeList[fileName]) {
                    callback({success: true, message: ''});
                    return;
                }
                var readDir = (type ? launchDir : cacheDir);
                
                this.init(readDir, fileName, getFileEntry, fail);

                function getFileEntry(fileEntry) {
                    fileEntry.file(readFile, fail);
                }

                function readFile(file) {
                    var reader = new FileReader();
                    reader.onloadend = function(evt) {
                        callback({success: true, message: evt.target.result});
                    };
                    reader.readAsDataURL(file);
                }

                function fail(data) {
                    // console.error(JSON.stringify(data), 'error');
                    callback({success: false, message: JSON.stringify(data)});
                }
            },
            clear: function(callback) {
                window.requestFileSystem && window.requestFileSystem(LocalFileSystem.PERSISTENT, 0, success, null);
                callback = callback || function(){};

                function success(fileSystem) {
                    fileSystem.root.getDirectory(cacheDir, {create: true},
                        function(dataDir) {
                            dataDir.removeRecursively(
                                function() {
                                    callback({success: true, message: 'clear cache success..'});
                                },
                                function() {
                                    callback({success: false, message: 'clear cache failure..'});
                                }
                            );
                        }
                    );
                }
            },
            launch: function(url, key, callback) {
                window.requestFileSystem(LocalFileSystem.PERSISTENT, 0, success, null);
                console.error("launch: ", url);
                function success(fileSystem) {
                    var fileTransfer = new FileTransfer();
                    var uri = encodeURI(url);
                    var hash =  fileSystem.root.nativeURL + launchDir + "/";
                    hash += imageFormat[key];
                    // console.error(hash);
                    fileTransfer.download(
                        uri,
                        hash,
                        function(entry) {
                            callback({success: true, message: 'success...'});
                        },
                        function(error) {
                            callback({success: false, message: 'failure...'});
                        },
                        false
                    );
                }
            },
        };
    });

});