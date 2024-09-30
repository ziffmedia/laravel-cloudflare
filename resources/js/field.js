import IndexField from "./components/IndexField";
import DetailField from "./components/DetailField";
import FormField from "./components/FormField";

Nova.booting((app, store) => {
    app.component('index-cache-clear', IndexField)
    app.component('detail-cache-clear', DetailField)
    app.component('form-cache-clear', FormField)
})
