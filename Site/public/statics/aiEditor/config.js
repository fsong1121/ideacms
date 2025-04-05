import { AiEditor } from '/statics/aiEditor/index.js';

// 导出初始化函数
export function InitAiEditor(config) {
    return new AiEditor({
        // 默认配置
        element: "#aiEditor",
        placeholder: "请在这里输入或粘贴内容...",
        content: '',
        toolbarSize: 'medium',
        toolbarKeys: ["undo", "redo", "brush", "eraser",
            "|", "heading", "font-family", "font-size",
            "|", "bold", "italic", "underline", "strike", "link", "todo", "emoji",
            "|", "highlight", "font-color",
            "|", "align", "line-height",
            //"|", "bullet-list", "ordered-list", "indent-decrease", "indent-increase", "break",
            "|", "image", "video", "table",
            "source-code", "fullscreen", "printer", "ai", "|",
            {
                toolbarKeys: ["code", "subscript", "superscript", "hr", "indent-decrease", "indent-increase", "break", "attachment", "quote", "code-block"]
            }
        ],
        toolbarExcludeKeys: ["heading", "todo", "emoji", "bullet-list", "ordered-list", "printer", "ai"],
        textSelectionBubbleMenu: {
            enable: true,
            items: ["Bold", "Italic", "Underline", "Strike", "code"],
        },
        //上传图片
        image: {
            allowBase64: false,
            defaultSize: '100%',
            uploadUrl: "/admin/upload/index.html?dir=article",
            uploadFormName: "file", //上传时的文件表单名称
            uploaderEvent: {
                onSuccess: (file, response) => {
                    //监听图片上传成功
                    //注意：
                    // 1、如果此方法返回 false，则图片不会被插入到编辑器
                    // 2、可以在这里返回一个新的 json 给编辑器
                    if(response.code > 0) {
                        return false;
                    } else {
                        let fileUrl = response.data.file;
                        if(!fileUrl.startsWith('http')) {
                            fileUrl = '/upload/pic/' + fileUrl;
                        }
                        return {
                            "errorCode": 0,
                            "data": {
                                "src": fileUrl,
                                "alt": ""
                            }
                        }
                    }
                },
                onFailed: (file, response) => {
                    //监听图片上传失败，或者返回的 json 信息不正确
                    if(response.code > 0) {
                        console.log('上传失败:' + response.msg);
                    }
                },
                onError: (file, error) => {
                    //监听图片上传错误，比如网络超时等
                    console.log('网络超时，上传失败');
                },
            },
            bubbleMenuItems: ["AlignLeft", "AlignCenter", "AlignRight", "delete"]
        },
        //上传视频
        video: {
            uploadUrl: "/admin/upload/index.html?dir=video",
            uploadFormName: "file", //上传时的文件表单名称
            uploaderEvent: {
                onSuccess: (file, response) => {
                    //监听视频上传成功
                    //注意：
                    // 1、如果此方法返回 false，则视频不会被插入到编辑器
                    // 2、可以在这里返回一个新的 json 给编辑器
                    if(response.code > 0) {
                        return false;
                    } else {
                        let fileUrl = response.data.file;
                        if(!fileUrl.startsWith('http')) {
                            fileUrl = '/upload/pic/' + fileUrl;
                        }
                        return {
                            "errorCode": 0,
                            "data": {
                                "src": fileUrl,
                                "poster": "",
                                "width": "100%",
                                "controls": "true"
                            }
                        }
                    }
                },
                onFailed: (file, response) => {
                    //监听视频上传失败，或者返回的 json 信息不正确
                    if(response.code > 0) {
                        console.log('上传失败:' + response.msg);
                    }
                },
                onError: (file, error) => {
                    //监听视频上传错误，比如网络超时等
                    console.log('网络超时，上传失败');
                },
            }
        },
        //上传附件
        attachment: {
            uploadUrl: "/admin/upload/index.html?dir=attachment",
            uploadFormName: "file", //上传时的文件表单名称
            uploaderEvent: {
                onSuccess: (file, response) => {
                    //监听附件上传成功
                    //注意：
                    // 1、如果此方法返回 false，则附件不会被插入到编辑器
                    // 2、可以在这里返回一个新的 json 给编辑器
                    //console.log(file);
                    if(response.code > 0) {
                        return false;
                    } else {
                        let fileUrl = response.data.file;
                        if(!fileUrl.startsWith('http')) {
                            fileUrl = '/upload/pic/' + fileUrl;
                        }
                        return {
                            "errorCode": 0,
                            "data": {
                                "href": fileUrl,
                                "fileName": ''
                            }
                        }
                    }
                },
                onFailed: (file, response) => {
                    //监听附件上传失败，或者返回的 json 信息不正确
                    if(response.code > 0) {
                        console.log('上传失败:' + response.msg);
                    }
                },
                onError: (file, error) => {
                    //监听附件上传错误，比如网络超时等
                    console.log('网络超时，上传失败');
                },
            }
        },
        // 合并传入的配置
        ...config
    });
}