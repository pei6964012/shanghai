<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>商飞学习管理系统</title>

        <style type="text/css" media="screen, projection">
            /*<![CDATA[*/
            @import "../css/default.css";
            @import "../css/main.css";
            /*]]>*/
        </style>


        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="../themes/default/easyui.css">
            <link rel="stylesheet" type="text/css" href="../themes/icon.css">

                <script type="text/javascript" src="../js/jquery.js"></script>
                <script type="text/javascript" src="../js/function.js"></script>
                <script type="text/javascript" src="../js/slides.min.jquery.js"></script>
                <script type="text/javascript" src="../js/jquery.min.js"></script>
                <script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
                </head>

                <body onload="load();">
                    <script type="text/javascript">
                        var parent = window.opener;
                        var $_GET = (function () {
                            var url = window.document.location.href.toString();
                            var u = url.split("?");
                            if (typeof (u[1]) == "string") {
                                u = u[1].split("&");
                                var get = {};
                                for (var i in u) {
                                    var j = u[i].split("=");
                                    get[j[0]] = j[1];
                                }
                                return get;
                            } else {
                                return {};
                            }
                        })();
                        var type = $_GET['type'];
                        var id_element = "";
                        var id_arr = [];
                        var page_index = 1;
                        var load = function () {
                            if (type === "course" || type === "course_group") {
                                id_arr = parent.cid_arr;
                            } else if (type === "user" || type === "user_group") {
                                id_arr = parent.uid_arr;
                            }
                            getList();
                        }

                        // get item list from server and update page
                        var getList = function () {
                            $.post(
                                    "course_to_user_function.php",
                                    {type: type, page_index: page_index},
                            function (result, status) {
                                if (status !== "success") {
                                    alert("网络不正常哟");
                                    return;
                                }

                                result = $.parseJSON(result);

                                d = result['data'];

                                setTreeData(d['items']);
                                setPagelist(d['amount']);
                                $("#item_list").tree({
                                    data: d['items'],
                                    checkbox: true,
                                    onCheck: function (node) {//update opener page's list
                                        //if node is not a group
                                        if (node.isLeaf) {
                                            if (node.checked == false) {
                                                addId(node.id, node.text);
                                            } else {
                                                removeId(node.id);
                                            }
                                        } else {
                                            //handle group check
                                            items = node.children;
                                            if (node.checked == false) {
                                                for (var i in items) {
                                                    addId(items[i]['id'], items[i]['title']);
                                                }
                                            } else {
                                                for (var i in items) {
                                                    removeId(items[i]['id'], items[i]['title']);
                                                }
                                            }
                                        }
                                    }
                                });
                            });
                        }
                        var printIdArr = function () {
                            idarrstr = ",";
                            for (var i in id_arr) {
                                if (id_arr[i] != "") {
                                    idarrstr += i + ':' + id_arr[i] + ',';
                                }
                            }
                            alert(idarrstr);
                        }
                        var addId = function (id, title) {
                            if (id_arr[id] != "" && id_arr[id] != null) {
                                return;
                            }
                            id_arr[id] = title;
                        }
                        var removeId = function (id) {
                            if (id_arr[id] == null || id_arr[id] == "")
                                return;
                            id_arr[id] = "";
                        }

                        var setPagelist = function (amount) {
                            $("#page_list").pagination({
                                total: amount,
                                pageNumber: page_index,
                                pageSize: 20,
                                showPageList: false,
                                showRefresh: false,
                                displayMsg: "",
                                onSelectPage: function (pageNumber, pageSize) {
                                    page_index = pageNumber;
                                    getList();
                                }
                            })
                        }
                        //set show text for tree data
                        var setTreeData = function (data) {
                            if (!isArray(data))
                                return;
                            for (var i = 0; i < data.length; i++) {
                                data[i]['text'] = data[i]['title'];
                                id = data[i]['id'];
                                data[i]['state'] = 'open';
                                if (isArray(data[i]['children'])) {
                                    data[i]['state'] = 'closed';
                                    data[i]['isLeaf'] = false;
                                } else {
                                    data[i]['isLeaf'] = true;
                                }
                                if (!!!id_arr[id] || id_arr[id] == "" || !data[i]['isLeaf']) {
                                    data[i]['checked'] = false;
                                } else {
                                    data[i]['checked'] = true;
                                    id_arr[data[i]['id']] = data[i]['text'];
                                }
                                if (!data[i]['isLeaf']) {
                                    setTreeData(data[i]['children']);
                                }
                            }
                        }
                        var isArray = function (obj) {
                            return Object.prototype.toString.call(obj) === '[object Array]';
                        }
                        //return checked course id and title to opener
                        var submitChecked = function () {
                            parent.page_submit(type, id_arr);
                            window.close();
                        }

                    </script>
<!--                    <label>搜索：</label><input type="text" id="txt_search" ></input><br/>
                    <label>排序：</label><br/>-->
                    <ul id="item_list" class="easyui-tree" ></ul>
                    <div id="page_list" class="easyui-pagination" ></div>
                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitChecked();">确认</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:window.close();">取消</a>
                </body>
                </html>
