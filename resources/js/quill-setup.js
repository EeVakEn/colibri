import Quill from "quill";
import ImageUploader from "quill-image-uploader";
import "quill/dist/quill.snow.css";
import axios from "axios";

Quill.register("modules/imageUploader", ImageUploader);

export function createQuillEditor(selector, content, onChange) {
    const quill = new Quill(selector, {
        theme: "snow",
        modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ["bold", "italic", "underline"],
                ["image", "blockquote", "code-block"],
                [{ list: "ordered" }, { list: "bullet" }],
                ["clean"],
            ],
            imageUploader: {
                upload: (file) => {
                    return new Promise((resolve, reject) => {
                        const formData = new FormData();
                        formData.append("image", file);

                        axios
                            .post(route('uploadImage'), formData, {
                                headers: { "Content-Type": "multipart/form-data" },
                            })
                            .then((response) => {
                                resolve(response.data.url);
                            })
                            .catch((error) => {
                                reject("Load image error");
                            });
                    });
                },
            },
        },
    });

    quill.root.innerHTML = content;

    quill.on("text-change", () => {
        if (onChange) {
            onChange(quill.root.innerHTML);
        }
    });

    return quill;
}
