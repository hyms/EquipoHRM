<template>
        <div class="content-w">
            <div class="content-box">
                <HelloWorld/>
            </div>
        </div>
</template>

<script>
// @ is an alias to /src
import HelloWorld from "../components/HelloWorld";
export default {
    name: "Home",
    components: {
        HelloWorld,
    }
};
</script>
