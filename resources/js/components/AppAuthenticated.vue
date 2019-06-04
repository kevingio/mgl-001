<template>
    <fullscreen ref="fullscreen" @change="fullscreenChange">
        <v-app>
            <v-toolbar app fixed clipped-left color="primary" dark>
                <v-toolbar-side-icon
                @click.stop="drawer = !drawer"
                ></v-toolbar-side-icon>
                <v-spacer></v-spacer>

                <v-tooltip bottom>
                    <template slot="activator">
                        <v-btn icon @click="toggleFullscreen">
                            <v-icon v-if="fullscreen == false">fullscreen</v-icon>
                            <v-icon v-else>fullscreen_exit</v-icon>
                        </v-btn>
                    </template>
                    <span v-if="fullscreen == false">Enter Fullscreen Mode</span>
                    <span v-else>Exit Fullscreen Mode</span>
                </v-tooltip>

                <v-menu open-on-hover offset-y offset-x>
                    <v-btn
                        class="text-none ma-0"
                        slot="activator"
                        depressed
                        flat
                        >
                        <span v-if="!!user" class="subheading text-xs-center overflow-text">
                            Hello,
                            <span class="font-weight-medium">
                                {{ user.name }}
                            </span>
                        </span>
                    </v-btn>

                    <v-list>
                        <v-list-tile
                            v-for="(item, i) in toolbarMenu"
                            :key="`accountmenu${i}`"
                            :to="item.route"
                            >
                            <v-list-tile-action>
                                <v-icon>{{ item.icon }}</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                </v-menu>
            </v-toolbar>

            <v-navigation-drawer
                v-model="drawer"
                app clipped fixed
                class="white"
            >
                <v-container fluid>
                    <v-img src="/images/logo-mss.png" max-height="150px" contain class="my-2">
                    </v-img>
                </v-container>
                <v-list>
                    <v-list-tile
                        v-for="(item, index) in routes"
                        router
                        :to="item.route"
                        :key="'menu'+index"
                        >
                        <v-list-tile-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-tile-action>

                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-divider dark class="my-3"></v-divider>

                    <v-list-tile to="/posting">
                        <v-list-tile-action>
                            <v-icon>shopping_cart</v-icon>
                        </v-list-tile-action>

                        <v-list-tile-content>
                            <v-list-tile-title>Posting</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-navigation-drawer>

            <v-content>
                <router-view @updateUser="updateUser"
                :key="$route.fullPath"></router-view>
            </v-content>
        </v-app>
    </fullscreen>
</template>

<script>
export default {
    data() {
        return {
            fullscreen: false,
            user: null,
            drawer: true,
            routes: [
                {
                    icon: "storage",
                    title: "Product",
                    route: "/product",
                },
                {
                    icon: "report_problem",
                    title: "Indent",
                    route: "/indent",
                },
                {
                    icon: "history",
                    title: "History",
                    route: "/history",
                },
                {
                    icon: "check_circle",
                    title: "Receiving Report",
                    route: "/receiving-report",
                },
            ],
            toolbarMenu: [
                {
                    icon: "exit_to_app",
                    title: "Logout",
                    route: "/logout"
                }
            ],
        }
    },
    methods: {
        toggleFullscreen () {
            this.$refs['fullscreen'].toggle()
        },
        fullscreenChange(fullscreen) {
            this.fullscreen = fullscreen
        },
        updateUser(data) {
            this.user = data
        },
    },
    created() {
        this.user = this.$getUser()
    },
}
</script>

<style scoped>
</style>
