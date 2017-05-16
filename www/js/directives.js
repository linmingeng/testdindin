define(['ionic', 'config'],  function(){
    'use strict';

    var dir = angular.module('main.directives', ['ionic']);
    
    dir.directive('compile', ['$compile', 'utils', function($compile, utils) {
        return function($scope, iElm, iAttrs) {
            $scope.$watch(
                function($scope) {
                    return $scope.$eval(iAttrs.compile);
                },
                function(str) {
                    if(str && 'string' == typeof str){
                        str = str.replace(/src\=\'\.{0,}\/{0,}upload/g, "image-lazy-loader=\"dots\" class=\"flex bg\" image-lazy-src\='"+utils.getStaticServer()+"/upload");
                        str = str.replace(/src\=\"\.{0,}\/{0,}upload/g, "image-lazy-loader=\"dots\" class=\"flex bg\" image-lazy-src\=\""+utils.getStaticServer()+"/upload");
                    }
                    iElm.html(str);
                    $compile(iElm.contents())($scope);
                }
            );
        }
    }]);

    dir.directive('slideProduce', ['config', '$window', function(config, $window){
        return {
            require: '?^ionSlideBox',
            restrict: 'A',
            template: '',
            replace: true,
            link: function($scope, iElm, iAttrs, controller) {
                $scope.check = function(){
                    return iElm.find('img').size();
                }

                var clearWatcher = $scope.$watch('check()', function(data){
                    if(data){
                        var ratio = iAttrs['slideProduce'] || 0;
                        var height = ratio > 0 ?$window.innerWidth*ratio : $window.innerHeight;
                        iElm.css('height', height);
                        angular.element(iElm).find('.img').css({'height': height});
                        clearWatcher();
                    }
                })
            }
        };
    }]);
    
    dir.directive('goodsItem', function(){
        return {
            restrict: 'A',
            scope: true,
            link: function($scope, iElm, iAttrs) {
                $scope.check = function(){
                    return document.body.clientWidth;
                }
                var baseWidth = 150;
                var fixLeft = 0;
                var fixHeight = 42+30+4+2;
                var factor = 1;
                var clearWatcher = $scope.$watch('check()', function(width){
                    if(width){
                        var colNum = Math.floor((width-fixLeft-2-2)/baseWidth);
                        var liWidth = Math.floor((width-fixLeft-2-2-4*colNum)/colNum);
                        var imgWidth = liWidth-6;
                        var imgHeight = Math.floor(imgWidth*factor);
                        var liHeight = imgHeight+fixHeight;
                        iElm.css({'width': liWidth, 'height': liHeight});
                        angular.element(iElm).find('.goods-div .img').css({'width': imgWidth, 'height': imgHeight});
                        //clearWatcher();
                    }
                })
            }
        };
    });
    
    dir.directive('squareItem', function(){
        return {
            restrict: 'A',
            scope: true,
            link: function($scope, iElm, iAttrs) {
                $scope.check = function(){
                    return document.body.clientWidth;
                }
                var baseWidth = 100;
                var fixLeft = 0;
                var clearWatcher = $scope.$watch('check()', function(width){
                    if(width){
                        var colNum = Math.floor((width-fixLeft-2-2)/baseWidth);
                        var liWidth = Math.floor((width-fixLeft-2-2-4*colNum)/colNum);
                        iElm.css({'width': liWidth, 'height': liWidth});
                        clearWatcher();
                    }
                })
            }
        };
    });
    
    dir.directive('appNotice', ['$timeout', function($timeout){
        return {
            restrict: 'A',
            scope: true,
            link: function($scope, iElm, iAttrs) {
                var num = parseInt(iAttrs['appNotice']);
                if(num > 1){
                    var curNum = 1;
                    var it = setInterval(function(){
                        iElm.css({'margin-top': '-'+curNum*30+'px'});
                        curNum++;
                        if(curNum >= num){
                            curNum = 0;
                        }
                    }, 3000);
                    $scope.$on('$destroy', function(){
                        clearInterval(it);
                    });
                }
            }
        };
    }]);

    dir.directive('formProduce', ['utils', 'config', 'req' , function(utils, config, req){
        return {
            restrict: 'A',
            scope: true,
            controller: ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
                var controls = {},
                    values = {},
                    onlyFun = null,
                    elmCount = 0;

                this.addControl = function(name, control){
                    if(controls[name] === undefined)
                        elmCount++;
                    controls[name] = control;
                };
                this.setValue = function(name, value){
                    values[name] = value;
                };
                this.setOnlyFun = function(fun){
                    onlyFun = fun;
                };
                $element.submit(startSubmit);
                this.startSubmit = startSubmit;

                function startSubmit(onlyCheck){
                    for(var i in values){
                        //if not require, don't check it
                        if(!controls[i].require)
                            continue;
                        //check is null
                        if(!values[i]){
                            utils.tip(controls[i].label + '\u4E0D\u80FD\u4E3A\u7A7A !', controls[i].element);  //不能为空
                            return false;
                        }
                        //check is seem to another one
                        if(controls[i].seemAs && values[i] != values[controls[i].seemAs]){
                            utils.tip((controls[i].label+ '\u4E0E' + controls[controls[i].seemAs].label + '\u5FC5\u987B\u4E00\u81F4 !'), controls[i].element);  //与...必须一致
                            return false;
                        }
                        //check is overflow
                        if(controls[i].max && values[i]*1>controls[i].max){
                            utils.tip(controls[i].label+ '\u4E0D\u80FD\u8D85\u8FC7 ' + controls[i].max + ' !', controls[i].element);  //不能超过
                            return false;
                        }
                        if(controls[i].min && values[i]*1<controls[i].min){
                            utils.tip(controls[i].label+ '\u4E0D\u80FD\u4F4E\u4E8E ' + controls[i].min + ' !', controls[i].element);  //不能低于
                            return false;
                        }
                        //check the RegExp
                        if(controls[i].pattern){
                            var t = controls[i].pattern.split('/'),
                                r = new RegExp(t[1], t[t.length-1]);
                            if(!r.test(values[i])){
                                utils.tip(controls[i].warning || (controls[i].label + '\u4E0D\u7B26\u5408\u89C4\u8303 !'), controls[i].element);  //不符合规范
                                return false;
                            }
                        }
                    }
                    var requestConf = {
                            keyName: $scope.formConf.keyName
                        };

                    if(config['resConfig'][requestConf.keyName]['method'] == 'GET')
                        requestConf.params = values;
                    else
                        requestConf.data = values;

                    //check is exist
                    var existCount = 0,
                        checkCount = 0;
                    for(var i in values){
                        checkCount++;
                        if(controls[i].isExist){
                            existCount++;
                            var checkConf = utils.confFactory([controls[i].isExist], {params: {}}),
                                tempKey = i;
                            checkConf.params[i] = values[i];
                            req(checkConf)
                                .success(function(data){
                                    if(data[tempKey]){
                                        utils.tip(controls[tempKey].label+ '\u5DF2\u88AB\u4F7F\u7528 !', controls[tempKey].element);  //已被使用
                                        isExist = true;
                                    }else if(elmCount == checkCount)
                                        request();
                                });
                        }
                    }

                    if(!existCount)
                        request();

                    function request(){
                        //if only need check the form, dont' request
                        if(onlyCheck == true && onlyFun)
                            return $scope.$eval(onlyFun);
                        //if the config have a function for excuting, don't request
                        if($scope.formConf.fun){
                            $scope.formConf.fun();
                            return false;
                        }

                        var btnTxt = (function(){
                            //get all submit element
                            var e = angular.element($element).find('input[type=submit],button:not(button[type=button])'),
                                t = [];

                            for(var i=0;i<e.length;i++){
                                t.push(e.eq(i).val() || e.eq(i).html());
                                if(e.eq(i).val())
                                    e.eq(i).val('提交中...');
                                else
                                    e.eq(i).html('提交中...');
                                e.attr('disabled', 'disabled');
                            }
                            return t;
                        })();
                        req(requestConf)
                            .success(function(d, s, h, c){
                                if($scope.formConf['success'])
                                    $scope.formConf['success'](d, s, h, c);
                                resetBtn(btnTxt);
                            })
                            .error(function(d, s, h, c){
                                if($scope.formConf['error'])
                                    $scope.formConf['error'](d, s, h, c);
                                resetBtn(btnTxt);
                            });
                    }

                    function resetBtn(btnTxt){
                        setTimeout(function(){
                            var e = angular.element($element).find('input[type=submit],button:not(button[type=button])');
    
                            for(var i=0;i<e.length;i++){
                                if(e.eq(i).val())
                                    e.eq(i).val(btnTxt[i]);
                                else
                                    e.eq(i).html(btnTxt[i]);
                                e.removeAttr('disabled');
                            }
                        },500);
                    }
                    return false;
                }
            }],
            link: function($scope, iElm, iAttrs, controller) {
                $scope.checkConfig = function(){
                    return $scope.$eval(iAttrs['formProduce']);
                };
                var clearWatcher = $scope.$watch('checkConfig()', function(conf){
                    $scope.formConf = conf;
                });
            }
        };
    }]);
    
    dir.directive('inputProduce',['utils', 'req', function(utils, req){
        return {
            restrict: 'A',
            scope: true,
            require: '^?formProduce',
            link: function($scope, iElm, iAttrs, formProduce) {
                $scope.checkConfig = function(){
                    return $scope.$eval(iAttrs['inputProduce']);
                };
                var clearWatcher = $scope.$watch('checkConfig()', function(conf){
                    if(conf){
                        var control = {
                                label: conf.label,
                                pattern: conf.pattern,
                                warning: conf.warning,
                                require: conf.require,
                                seemAs: conf.seemAs,
                                isExist: conf.isExist,
                                min: conf.min,
                                max: conf.max,
                                element: iElm
                            },
                            name = conf.name;
                        formProduce.addControl(name, control);

                        $scope.getVal = function(){
                            return $scope.$eval(iAttrs['ngModel']);
                        };
                        $scope.$watch('getVal()', function(data){
                            formProduce.setValue(name, data);
                        });
                        if(conf.isExist){
                            iElm.bind('blur', function(){
                                if(!iElm.val())
                                    return;
                                if(control.pattern){
                                    var t = control.pattern.split('/'),
                                        r = new RegExp(t[1], t[t.length-1]);
                                    if(!r.test(iElm.val()))
                                        return;
                                }
                                var checkConf = utils.confFactory([conf.isExist], {params: {}});
                                checkConf.params[name] = iElm.val();
                                req(checkConf)
                                    .success(function(data){
                                        if(data[name])
                                            utils.tip(conf.label+ '\u5DF2\u88AB\u4F7F\u7528 !', iElm);  //已被使用
                                    });
                            });
                        }
                        clearWatcher();
                    }
                }, true);
                
            }
        };
    }]);
    
    dir.directive('phoneProduce',['utils', 'req', '$timeout'
        , function(utils, req, $timeout){
        return {
            restrict: 'A',
            scope: true,
            require: '^?formProduce',
            link: function($scope, iElm, iAttrs, formProduce) {
                $scope.checkConfig = function(){
                    return $scope.$eval(iAttrs['phoneProduce']);
                }
                var clearWatcher = $scope.$watch('checkConfig()', function(conf){
                    if(conf){
                        var control = {
                                label: conf.label,
                                pattern: conf.pattern,
                                warning: conf.warning,
                                require: conf.require,
                                element: iElm
                            },
                            name = conf.name,
                            requestConf = utils.confFactory([conf.keyName]),
                            sendBtn = angular.element(iAttrs['phoneSend']),
                            sendTxt = sendBtn.val() || sendBtn.html();

                        if(requestConf.method == 'GET')
                            requestConf['params'] = {};
                        else
                            requestConf['data'] = {};
                        formProduce.addControl(name, control);

                        $scope.getVal = function(){
                            return ($scope.$eval(iAttrs['ngModel']));
                        }
                        $scope.$watch('getVal()', function(data){
                            var t = data;
                            formProduce.setValue(name, t);
                            if(requestConf.method == 'GET')
                                requestConf.params[name] = t;
                            else
                                requestConf.data[name] = t;
                        });

                        sendBtn.click(function(){
                            if(!iElm.val() && control.pattern){
                                utils.tip(control.label + '\u4E0D\u80FD\u4E3A\u7A7A !', iElm);  //不能为空
                                return;
                            }
                            if(control.pattern){
                                var t = control.pattern.split('/'),
                                    r = new RegExp(t[1], t[t.length-1]);
                                if(!r.test(iElm.val())){
                                    utils.tip(control.warning || (control.label + '\u4E0D\u7B26\u5408\u89C4\u8303 !'), iElm);  //不符合规范
                                    return;
                                }
                            }
                            if(conf.isExist){
                                //未启用
                                var checkConf = utils.confFactory([conf.isExist], {params: {}});
                                checkConf.params[name] = iElm.val();
                                req(checkConf).success(function(data){
                                    console.error(JSON.stringify(data));
                                    if(data.code == 200){
                                        request();
                                    }
                                });
                            }else{
                                request();
                            }

                            function request(){
                                sendBtn.val()?sendBtn.val('...'): sendBtn.html('...');
                                sendBtn.attr('disabled', 'disabled');
                                req(requestConf).success(function(data){
                                    if(data.code == 200){
                                        startTimeout(60);
                                    }else{
                                        sendBtn.val()?sendBtn.val(sendTxt): sendBtn.html(sendTxt);
                                        sendBtn.removeAttr('disabled');
                                    }
                                }).error(function(){
                                    sendBtn.val()?sendBtn.val(sendTxt): sendBtn.html(sendTxt);
                                    sendBtn.removeAttr('disabled');
                                });
                            }
                        });
                        clearWatcher();
                    }
                    function startTimeout(t){
                        if(!t){
                            sendBtn.removeAttr('disabled');
                            sendBtn.val()?sendBtn.val(sendTxt): sendBtn.html(sendTxt);
                            return;
                        }
                        var str = t--;
                        sendBtn.val()?sendBtn.val(str): sendBtn.html(str);
                        $timeout(function(){startTimeout(t)}, 1000);
                    }
                });
            }
        };
    }]);

    //未使用
    dir.directive('readyRemove', function(){
        return {
            restrict: 'A',
            link: function($scope, iElm, iAttrs, controller) {
                var obj = angular.element(iAttrs['readyRemove']);

                function bind(){
                    iElm.fadeOut(300, function(){
                        $(this).remove();
                    });
                    obj.unbind('load', bind);
                }

                obj.bind('load', bind);
            }
        };
    });
    
    dir.directive('formOnlyCheck', function(){
        return {
            restrict: 'A',
            require: '^?formProduce',
            link: function($scope, iElm, iAttrs, formProduce) {
                formProduce.setOnlyFun(iAttrs['formOnlyCheck']);

                iElm.click(function(){
                    formProduce.startSubmit(true);
                });
            }
        };
    });

    //未使用
    dir.directive('returnBlur', function(){
        return {
            restrict: 'A',
            link: function($scope, iElm, iAttrs, controller) {
                iElm.keyup(function(e){
                    if(e.keyCode == 13)
                        iElm.blur();
                });
            }
        };
    });

    //未使用
    dir.directive('autoFocus', function($timeout) {
        return {
            restrict: 'AC',
            link: function(_scope, _element) {
                $timeout(function(){
                    _element[0].focus();
                }, 300);
            }
        };
    });

    dir.directive('cacheSrc', ['config', 'fileService', function(config, fileService) {
        return {
            restrict: 'A',
            link: function($scope, iElm, iAttrs) {
                $scope.checkSrc = function(){
                    return iAttrs['cacheSrc'];
                }
                var clearWatcher = $scope.$watch('checkSrc()', function(data){
                    if(data){
                        if(config.cacheImage){
                            if(ionic.Platform.isIPad() || ionic.Platform.isIOS() || ionic.Platform.isAndroid()){
                                fileService.read(iAttrs.cacheSrc, function(res) {
                                    var image;
                                    if (res.success) {
                                        image = res.message;
                                    } else {
                                        fileService.save(iAttrs.cacheSrc, function(res) {
                                            // console.error("save cache: " + res.success);
                                        });
                                    }
                                    iAttrs.$set('src', image? image: iAttrs.cacheSrc);
                                });
                            }else{
                                iAttrs.$set('src', iAttrs.cacheSrc);
                            }
                        }else{
                            iAttrs.$set('src', iAttrs.cacheSrc);
                        }
                        clearWatcher();
                    }
                });
            }
        }
    }]);

    dir.directive('oneKeyClearInput',['$timeout', function($timeout){
        return {
            restrict: 'A',
            link: function($scope, iElm, iAttrs, controller) {
                var btn = angular.element('<i class="icon iconfont icon-iosclose one-key-clear"></i>'),
                    handleId = iElm.html().length+ iElm.attr('class')?iElm.attr('class').split(' ').join(''): ''+ 'oneKey',
                    iconSize = 18,   //set the size for icon
                    touchScope = 6,  //set the touch scope for icon button
                    container = null;

                function init(){
                    // get parent
                    container = iElm.offsetParent();

                    // calc position
                    var top = iElm.offset().top + iElm.outerHeight()/2
                            - iconSize/2 - container.offset().top
                            - touchScope*1.4,
                        left = iElm.offset().left + iElm.outerWidth() 
                            - iconSize - touchScope - container.offset().left - 5;
                    
                    // set styles
                    btn.css({'position': 'absolute', 'top': top, 'left': left, 'fontSize': iconSize, 'color': '#ccc', 'padding': touchScope, 'display': 'none', 'zIndex': 2});

                    // push to dom
                    container.find('.one-key-clear').remove();
                    container.append(btn);

                    // bind event
                    if(container.is('label')){
                        container.click(function(e){
                            var x = e.clientX,
                                y = e.clientY,
                                top = btn.offset().top;
                                left = btn.offset().left;

                            // check touch scope for label
                            // because the click event of object can not be trigger
                            // when the obj is belong to the label
                            if(x > left - touchScope
                                && x < left + iconSize + touchScope
                                && y > top - touchScope 
                                && y < top + iconSize + touchScope){

                                $scope.$apply(iAttrs['ngModel'] + '=null');
                            }
                        });
                    }
                    btn.click(function(e){
                        $scope.$apply(iAttrs['ngModel'] + '=null');
                        e.stopPropagation();
                    });
                }

                $scope.checkVal = function(){
                    return $scope.$eval(iAttrs['ngModel']);
                }

                $scope.$watch('checkVal()', function(data){
                    if(data){
                        if(!container){
                            init();
                        }
                        btn.css("display","block");
                    }
                    else
                        btn.css("display","none")
                });
            }
        };
    }]);
});