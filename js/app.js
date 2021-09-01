var host = "api/session/core.php";
var check = "api/session/check.php";
var destroy = 'api/session/destroy.php';
var main = 'api/main.php';

var app = angular.module('tortasAhogadas', ['ngRoute', 'ngResource','chart.js'])
        .config(['$routeProvider', '$locationProvider', '$qProvider', function ($routeProvider, $locationProvider, $qProvider) {
                $routeProvider
                        .when('/login', {templateUrl: 'views/login.html', controller: 'loginCtrl'})
                        .when('/mesas', {templateUrl: 'views/flow/mesas.html', controller: 'mesasCtrl'})
                        .when('/ordenar/:id', {templateUrl: 'views/flow/ordenes.html', controller: 'ordenarCtrl'})
                        // .when('/ordenes', {templateUrl: 'views/flow/ordenes_rate.html', controller: 'ordenesRateCtrl'})     
                        // .when('/administrar', {templateUrl: 'views/flow/admin.html', controller: 'adminCtrl'})        
                        // .when('/modules', {templateUrl: 'views/flow/adminModules.html', controller: 'adminModulesCtrl'})                     
                        .otherwise({redirectTo: '/login'})
                //For uses on 1.6.6
                $locationProvider.hashPrefix('');
                $qProvider.errorOnUnhandledRejections(false);
            }])
            
        //Siempre que se ejecute la aplicacion primera hace esto
        .run(['$rootScope', '$location', 'loginService', function ($rootScope, $location, loginService) {
                var routepermission = ['/mesas', '/ordenar','/ordenes'];
                $rootScope.$on('$routeChangeStart', function () {
                    var page = routepermission.indexOf($location.path());
//                    console.log(">> Exit tab " + routepermission.indexOf($location.path()));
//                    console.log(">> Logged   " + loginService.isLogged());
                    //if (routepermission.indexOf($location.path()) != -1 && !loginService.isLogged()) {
                    if (routepermission.indexOf($location.path()) != -1) {
                        var conServ = loginService.isLogged();
                        conServ.then(function (data) {
                            if (data.data.responce === 'autentified') {
                                $location.path(routepermission[page]);
                            } else {
                                $location.path("/login");
                            }
                        });
                    }
                })
            }])
        //Login Controller
        .controller('loginCtrl', ['$scope', 'loginService', function ($scope, loginService) {
                $scope.msn = '';
                $scope.user = {};
                $scope.login = function (user) {
                    loginService.login(user, $scope);
                }
            }])

        //Mesas Controller
        .controller('mesasCtrl', ['$scope', '$rootScope', '$location', 'loginService', 'httpApp', function ($scope, $rootScope, $location, loginService, httpApp) {
                $scope.pageTitle = "Mesas";
                $scope.mesas = [];
                $scope.datos = [];

                // Obtencion Mesas con Nombre
                httpApp.rxhRequest({kind: 'mesas', arr: 'arrt'}, function (data) {
                    if (data.statusText === 'OK') {
                        $scope.mesas = data.data;
                    }
                }, function (err) {
                    toazt("Error Interno Verifique Credenciales de Acceso " + err.message);
                    console.log(err.message);
                });

                //Disponibles
                $scope.disponibles = function () {
                    var total = 0;
                    angular.forEach($scope.mesas, function (k) {
                        if (parseInt(k.idempleado) != 1) {
                            total++;
                        }
                    });
                    return $scope.total = total;
                }

//                $scope.agregarMesa = function () {
//                    var e = $scope.mesas.length + 1;
//                    $scope.mesas.push({id: e, signed: $scope.empleadoAsignado == 'undefined' ? 'Disponible' : $scope.empleadoAsignado})
//                    $scope.empleadoAsignado = '';
//                }

                //Realiza el calculo del porcentaje
                $scope.percentage = function () {
                    var percentage = 0;
                    var dis = $scope.disponibles();
                    var _100 = $scope.mesas.length;
                    var p = dis * 100 / _100;
                    return $scope.p = p;
                }

                //Log Out De Usuario Die
                $scope.logout = function () {
                    loginService.logout();

                }

                //Acredita una Mesa para Ordenar
                $scope.inOrderTable = function (idmesa, idempleado) {
                    httpApp.rxhRequest({kind: 'signed_mesa', idmesa: idmesa, idempleado: idempleado, type: 'asociar_empleado'},
                            function (data) {
                                if (data.statusText === 'OK') {
                                    var req = data.data;
                                    if (req.status === 'rush') {
                                        toazt("Esta mesa se ecuentra en uso por " + req.nombres);
                                    } else if (req.status === 'en_uso') {
                                        $location.path("/ordenar/" + idmesa);
                                    } else if (req.status === 'asignada') {
                                        toazt("La mesa a sido asignada  " + $rootScope.User[0].nombres);
                                        $location.path("/ordenar/" + idmesa);
                                    } else if (req.status === 'disponible') {
                                        toazt("La mesa se encuentra vacia");
                                        $location.path("/mesas");
                                    }
                                }
                            }, function (err) {
                        toazt("Error Interno Verifique Credenciales de Acceso " + err.message);
                        console.log(err.message);
                    });
                }

                //Refresca Los movimientos de las mesas para evitar inconsistencias
                $scope.refreshMesas = function () {
                    httpApp.rxhRequest({kind: 'mesas', arr: 'arrt'}, function (data) {
                        if (data.statusText === 'OK') {
                            $scope.mesas = data.data;
                            toazt("Se Actualizaron las Mesas");
                        }
                    }, function (err) {
                        toazt("Error Interno Verifique Credenciales de Acceso " + err.message);
                        console.log(err.message);
                    });
                }
            }])


        .controller('ordenarCtrl', ['$scope', '$routeParams', '$rootScope', '$location', 'httpApp', 'sessionService', function ($scope, $routeParams, $rootScope, $location, httpApp, sessionService) {

                $scope.pageTitle = 'Ordenes';
                $scope.num = $routeParams.id;
                $scope.categorias = [];
                $scope.productos = [];
                $scope.configuracion = '';
                $scope.ordenDetail = [];
                $scope.ordenResume = {orden: [], cantidad: 0, total: 0};
                //Valida la Orden Actual                 
                httpApp.rxhRequest({kind: "check_order", idmesa: $scope.num, idempleado: $rootScope.User[0].idempleado}, function (data) {
                    if (data.statusText === 'OK') {
                        //Obtiene Categorias
                        // console.log(data.data);
                        var req = data.data;
                        $rootScope.idorder = req.id;
                        $scope.ordenDetail = req.order;
                        $scope.ordenResume = {orden: $scope.ordenDetail, cantidad: req.cantidad, precio: req.precio};
                        httpApp.rxhRequest({kind: "categorias"}, function (data) {
                            if (data.statusText === 'OK') {
                                $scope.categorias = data.data;
                                // Obtiene Productos 
                                httpApp.rxhRequest({kind: "productos"}, function (data) {
                                    if (data.statusText === 'OK') {
                                        $scope.productos = data.data;
                                    }
                                }, function (err) {
                                    console.log(err.message);
                                    toazt("Error Interno Verifique Credenciales de Acceso " + err.message);
                                });
                            }
                        }, function (err) {
                            console.log(err.message);
                            toazt("Error Interno Verifique Credenciales de Acceso " + err.message);
                        });
                    }
                }, function (err) {
                    console.log(err.message);
                    toazt("Error Interno Verifique Credenciales de Acceso " + err.message);
                });

                //Cerrar Session Todas las Pantallas we
                $scope.logout = function () {
                    sessionService.destroy('uid');
                    $location.path('/login');
                }

                //Desasociar empleados de la mesa 
                $scope.unOrderTable = function (idmesa) {
                    httpApp.rxhRequest({kind: 'signed_mesa', idmesa: idmesa, idempleado: 1, type: 'desasociar_empleado'}, function (data) {
                        if (data.statusText === 'OK') {
                            toazt("La mesa se encuentra vacia");
                            $location.path("/mesas");
                        }

                    }, function (err) {
                        console.log(err.message);
                        toazt("Error Interno Verifique Credenciales de Acceso " + err.message);
                    });

                }

                //Elige los objetos para Productos
                $scope.chooseProducts = function (id) {
                    var arrType = [{id: 1, icon: 'local_dining'}, {id: 2, icon: 'local_drink'}, {id: 3, icon: 'cake'}];
                    var icon = '';
                    $scope.catProducts = [];
                    angular.forEach(arrType, function (t) {
                        if (parseInt(t.id) === parseInt(id)) {
                            icon = t.icon;
                        }
                    });
                    angular.forEach($scope.productos, function (p) {
                        if (parseInt(p.idcategoria) === parseInt(id)) {
                            p.icon = icon;
                            $scope.catProducts.push(p);
                        }
                    });
                }

                //Configuracion Producto Indicaador
                $scope.configProduct = function (prod) {
                    $scope.indi = [];
                    $scope.configuracion = '';
                    angular.forEach($scope.catProducts, function (p) {
                        if (parseInt(p.idproducto) === parseInt(prod)) {
                            $scope.indi.push(p);
                        }
                    });
                    $scope.cantidades = {options: [
                            {val: 0, text: "mas de 1"},
                            {val: 1, text: "1"},
                            {val: 2, text: "2"},
                            {val: 3, text: "3"},
                            {val: 4, text: "4"},
                            {val: 5, text: "5"},
                            {val: 6, text: "6"},
                            {val: 7, text: "7"},
                            {val: 8, text: "8"},
                            {val: 9, text: "9"},
                            {val: 10, text: "10"}],
                        selekter: ''};
                    $scope.indicadores = [];
                    ($scope.indi[0].opcion1 == 'empty' ? '' : $scope.indicadores.push({key: $scope.indi[0].opcion1}));
                    ($scope.indi[0].opcion2 == 'empty' ? '' : $scope.indicadores.push({key: $scope.indi[0].opcion2}));
                    ($scope.indi[0].opcion3 == 'empty' ? '' : $scope.indicadores.push({key: $scope.indi[0].opcion3}));
                    ($scope.indi[0].opcion4 == 'empty' ? '' : $scope.indicadores.push({key: $scope.indi[0].opcion4}));
                    ($scope.indi[0].opcion5 == 'empty' ? '' : $scope.indicadores.push({key: $scope.indi[0].opcion5}));
                    ($scope.indi[0].opcion6 == 'empty' ? '' : $scope.indicadores.push({key: $scope.indi[0].opcion6}));
                    ($scope.indi[0].opcion7 == 'empty' ? '' : $scope.indicadores.push({key: $scope.indi[0].opcion7}));
                    ($scope.indi[0].opcion8 == 'empty' ? '' : $scope.indicadores.push({key: $scope.indi[0].opcion8}));
                    ($scope.indi[0].opcion9 == 'empty' ? '' : $scope.indicadores.push({key: $scope.indi[0].opcion9}));
                    ($scope.indi[0].opcion10 == 'empty' ? '' : $scope.indicadores.push({key: $scope.indi[0].opcion10}));
                }

                //set opcion 
                $scope.setCnf = function (cnf) {
                    $scope.configuracion = cnf;
                }

                //Agregar Producto a Mesa con estatus 0 inexistente en la orden
                $scope.addOrder = function (mesa) {
                    var user = $rootScope.User[0];
                    var prod = $scope.indi[0];
                    var nuevos = [];
                    var _counter = $scope.cantidades.selekter.val || 1;
                    // Elementos Seleccionados total por cada producto 
                    for (var i = 0; i < _counter; i++) {
                        //// categoria 
                        nuevos.push({idmesa: mesa, idempleado: user.idempleado, idproducto: prod.idproducto, descripcion: prod.descripcion, estatus: 0, categoria:prod.idcategoria, indicador: $scope.configuracion, cantidad: 1, precio: prod.precio, nota: ''});
                    }
                    ///Genera nuevos Elementos y los agrega a la detalle de la orden
                    angular.forEach(nuevos, function (n) {
                        ///// categoria 
                        $scope.ordenDetail.push({idmesa: n.idmesa, idempleado: n.idempleado, idproducto: n.idproducto, estatus: n.estatus, categoria:n.categoria, indicador: n.indicador,  descripcion: n.descripcion, cantidad: n.cantidad, precio: n.precio, nota: n.nota})
                    });
                    var cant = 0, total = 0;
                    angular.forEach($scope.ordenDetail, function (d) {
                        cant += parseInt(d.cantidad);
                        total += parseInt(d.precio);
                    });
                    // Crea un resumen de la orden
                    $scope.ordenResume = {orden: $scope.ordenDetail, cantidad: cant, total: total};
                    toazt("Se Agregaron (" + _counter + ") - " + prod.descripcion + " total de productos =  " + $scope.ordenResume.cantidad);
                }

                //Borrar Productos del Detalle con estatus 0 antes de ser pedidos
                $scope.deleteItemOrder = function (index) {
                    $scope.ordenDetail.splice(index, 1);
                    $scope.ordenResume.cantidad = $scope.ordenResume.cantidad - 1;
                    toazt(" Eliminando Producto Orden ");
                }

                //Ordenar Productos
                $scope.orderIn = function () {
                    var user = $rootScope.User[0];
                    var ordSend = [];
                    //a) Filtramos elementos que ya fueron ordenados con los que apenas seran ordenados, estatus = 0 pendientes, estatus = 1 servidos               
                    angular.forEach($scope.ordenDetail, function (o) {
                        if (o.estatus == 0) {
                            ordSend.push(o);
                        }
                    })
                    console.log('ordSend');
                    console.log(ordSend);
                    // validamos que exista mas de un elemento
                    if (ordSend.length > 0) {
                        //Enviando Orden y verificando que exista
                        httpApp.rxhRequest({kind: 'verify_order', idmesa: $scope.num, idempleado: user.idempleado},
                                function (data) {
                                    if (data.statusText === 'OK') {
                                        var req = data.data;
                                        $rootScope.idorder = req.id;
                                        //b) Con la Orden Creada almacenamos todos los elementos de uno por uno
                                        httpApp.rxhRequest({kind: 'add_order', 'order': ordSend, 'idorden': req.id},
                                                function (data) {                          
                                                    if (data.statusText === 'OK') {
                                                        var req = data.data;
                                                        var indexes = [];
                                                        //vnuevos.push({idmesa: mesa,idempleado: user.idempleado,idproducto: prod.idproducto, descripcion: prod.descripcion,estatus: 0,indicador: $scope.configuracion,cantidad: 1,precio: prod.precio,nota: '' });
                                                        angular.forEach(ordSend, function (o) {
                                                            if (indexes.indexOf(o.indicador + '|' + o.idproducto + '|' + o.nota) === -1) {
                                                                indexes.push(o.indicador + '|' + o.idproducto + '|' + o.nota);
                                                            }
                                                            o.estatus = 1;
                                                        });
                                                        var ticket = [];
                                                        // Aramando el Ticket 
                                                        angular.forEach(indexes, function (idx) {
                                                            var precio = 0, cantidad = 0, indicador = '', descripcion = '', idproducto = 0, categoria = 0, nota = '';
                                                            var pipe = idx.split('|');
                                                            var _indicador = pipe[0];
                                                            var _id = pipe[1];
                                                            var _nota = pipe[2];
                                                            angular.forEach(ordSend, function (o) {
                                                                if (o.indicador === _indicador && o.idproducto === _id && o.nota === _nota) {
                                                                    cantidad++;
                                                                    precio = o.precio;
                                                                    indicador = _indicador;
                                                                    idproducto = _id;
                                                                    descripcion = o.descripcion;
                                                                    nota = _nota;
                                                                    // categoria 
                                                                    categoria = o.categoria;
                                                                }
                                                            });
                                                            if (cantidad > 0) {
                                                                ticket.push({cantidad: cantidad, idproducto: idproducto, descripcion: descripcion, precio: precio, estatus: 1, categoria: categoria, size: indicador, nota: nota});
                                                            }
                                                        });
                                                        httpApp.rxhRequest({kind: 'print_center', 'ticket': ticket, 'idorden':$rootScope.idorder, 'idmesa': $scope.num, 'empleado':$rootScope.User[0], target:'Orden'},function(data){
                                                            if(data.statusText === 'OK' ){                                                                
                                                                toazt(" Se envio la Orden a Cocina ")
                                                                
                                                            }                                                            
                                                        });                                                                                                                
                                                    }
                                                },
                                                function (err) {
                                                    console.log(err.message);
                                                });
                                       
                                    }
                                },
                                function (err) {});
                    } else {
                        toazt(" No hay productos nuevos para esta orden AGREGA mas productos ")
                    }
                }
                
                //Pagar Orden 
                $scope.payOrder = function(){                    
                    console.log($scope.ordenDetail);
                    console.log($rootScope.idorder);
                    httpApp.rxhRequest({kind: 'pay_order',  idorden:$rootScope.idorder, 'idmesa': $scope.num, 'empleado': $rootScope.User[0]}, function (data) {
                        $scope.reset = data.data;
                    if(data.statusText === 'OK'){                            
                            httpApp.rxhRequest({kind: 'print_center', 'ticket': $scope.ordenDetail, 'idorden': $rootScope.idorder, 'idmesa': $scope.num, 'empleado': $rootScope.User[0], target: 'Pagar'}, 
                                function (data) {
                                if (data.statusText === 'OK') {                                   
                                    toazt(" Se Imprimio el Ticket ");
                                    $location.path('/mesas');
                                }
                            });                                                                                                     
                        }                        
                    });
                }
            

            /** Save Modal Elements Quetion **/
            $scope.saveModalElementQuestion = function(type,act){
                    $scope.beforeToDoMake = true;                                        
                    if(act == 'put'){
                        $scope.elementQuestion = {question:" ¿ Desea actualizar el catalogo " + $scope.ORTitle + " ? "};    
                    } else if(act == 'ins'){
                        $scope.elementQuestion = {question:" ¿ Desea agregar un elemento nuevo al catalogo " + $scope.ORTitle + " ? "};    
                    } else if(act == 'del'){
                        $scope.elementQuestion = {question:" ¿ Desea borrar un el elemento del catalogo " + $scope.ORTitle + " ? "};    
                    }
            }
            
            /** save Modal Element Action**/
            $scope.saveModalElement = function(type,act){                
                // type = ( type === 'categorias')  ? 'categorias_productos': type ;
                $scope.currentElement.idperfiles=2;                                 
                var params = { kind: 'crud_catalogs', 
                                     type:type, 
                                     action:act, 
                                     currentE: $scope.currentElement };             
                                     console.log(params)
                httpApp.rxhRequest(params,
                function(data){                                        
                    /** Finaliza y Actualiza el Catalogo**/
                    // $scope.ORTitle = ($scope.ORTitle ==='categorias') ? 'categorias_productos' : $scope.ORTitle;
                    httpApp.rxhRequest({kind: $scope.ORTitle, arr: 'arrt'}, function (data) {                        
                        console.log("entramos");
                        console.log(data.data);
                        if (data.statusText === 'OK') {
                            $scope.elements  = data.data;   
                            // $scope.ORTitle = ($scope.ORTitle ==='categorias_productos') ? 'categorias' : $scope.ORTitle;
                        }
                    }, function (err) {
                        toazt("Error Interno Verifique Credenciales de Acceso " + err.message);
                        console.log(err.message);
                    });   
                }, function (err) {
                    toazt("Error Interno Verifique Credenciales de Acceso " + err.message);
                    console.log(err.message);
                });
            }

        }])

        // .controller('ordenesRateCtrl', ['$scope', '$routeParams', '$rootScope', '$location', 'httpApp', 'sessionService', function ($scope, $routeParams, $rootScope, $location, httpApp, sessionService) {
        //     alert("ordenesRateCtrl");
        // }])

        // .controller('adminCtrl', ['$scope', '$routeParams', '$rootScope', '$location', 'httpApp', 'sessionService', function ($scope, $routeParams, $rootScope, $location, httpApp, sessionService) {
        //     alert("adminCtrl");
        // }])

        .controller('adminModulesCtrl',['$scope', '$routeParams', '$rootScope', '$location', 'httpApp', 'sessionService',function($scope, $routeParams, $rootScope, $location, httpApp, sessionService){
            alert("are in");
        }])
        //Factory LoginService 
        .factory('loginService', ['$rootScope', '$http', '$location', 'sessionService', function ($rootScope, $http, $location, sessionService) {
                $rootScope.User = {};
                return{
                    login: function (user, $scope) {
                        var $promise = $http.post(host, user);//send data to main.php
                        $promise.then(function (data) {
                            var uid = data.data;
                            if (uid.responce == 'success') {
                                $rootScope.User = uid.user_;
                                sessionService.set('uid', uid.session);
                                $location.path('/mesas');
                            } else {
                                $location.path('/login');
                                $scope.msn = "usuario o password es incorrecto";
                            }
                        });
                    },
                    logout: function () {
                        sessionService.destroy('uid');
                        $location.path('/login');
                    },
                    isLogged: function () {
                        var $checkSessionService = $http.post(check);
                        return $checkSessionService;
                    }
                }
            }])

        
        //Factory SessionService 
        .factory('sessionService', ['$http', function ($http) {
                return{
                    set: function (key, value) {
                        return sessionStorage.setItem(key, value);
                    },
                    get: function (key) {
                        return sessionStorage.getItem(key);
                    },
                    destroy: function (key) {
                        $http.post(destroy).then(function (data) {
                            return sessionStorage.removeItem(key);
                        })
                    }
                }
            }])

        //Cover All The Flow
        .factory('httpApp', ['$http', function ($http) {
                return{
                    rxhRequest: function (params, callback) {
                        $http.post(main, {params}).then(callback);
                    },
                    rxhGetRequest:function (params, callback) {
                        $http.get(main, {params}).then(callback);
                    }
                    
                }
            }])

        //Directives loginDirective  
        .directive('loginDirective', function () {
            return {
                templateUrl: 'views/tpl/login.tpl.html'

            }
        });

/** alertas **/
function toazt(message) {
    Materialize.toast(message, 2500);
}


    