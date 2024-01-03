<template>
    <v-container fluid pa-0>
        <v-layout>
            <v-flex xs12>
                <v-bottom-nav :active.sync="radios" :value="true" color="transparent">
                    <v-btn color="primary" flat value="carrusel">
                        <span>Carrusel</span>
                        <v-icon>mdi-view-carousel</v-icon>
                    </v-btn>
                    <v-btn color="primary" flat value="blog">
                        <span>Blog</span>
                        <v-icon>mdi-bulletin-board</v-icon>
                    </v-btn>
                    <v-btn color="primary" flat value="multimedia">
                        <span>Multimedia</span>
                        <v-icon>mdi-file-video</v-icon>
                    </v-btn>
                </v-bottom-nav>
            </v-flex>
        </v-layout>
        <v-container fluid pa-0>
            <v-layout v-if="radios == 'carrusel'">
                <v-flex>
                    <v-container fluid>
                        <v-layout>
                            <v-flex>
                                <v-card>
                                    <v-toolbar flat color="white">
                                        <v-dialog v-model="crearcarrusel" persistent max-width="500px">
                                            <template v-slot:activator="{ on }">
                                                <v-btn round color="primary" dark class="mb-2" v-on="on">
                                                    Agregar Imagen
                                                </v-btn>
                                                <v-spacer></v-spacer>
                                                <v-divider class="mx-2" inset vertical></v-divider>
                                                <v-toolbar-title>
                                                    Carrusel
                                                </v-toolbar-title>
                                            </template>
                                            <v-card>
                                                <v-toolbar color="success">
                                                    <span class="headline" color="#FFFFFF">
                                                        Nuevo Carrusel
                                                    </span>
                                                </v-toolbar>
                                                <v-card-text class="pt-0 pb-0">
                                                    <v-form enctype="multipart/form-data">
                                                        <v-container grid-list-md>
                                                            <v-layout wrap>
                                                                <v-flex xs6 sm6 md6>
                                                                    <v-text-field v-model="carrusel.nombre"
                                                                        label="Nombre" required>
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs6 sm6 md6>
                                                                    <v-text-field v-model="carrusel.enlace"
                                                                        label="Enlace">
                                                                    </v-text-field>
                                                                </v-flex>
                                                            </v-layout>
                                                            <v-layout wrap>
                                                                <!-- fecha inicio -->
                                                                <v-flex xs12 sm6 md6>
                                                                    <v-text-field v-model="carrusel.fecha_inicio"
                                                                        label="Fecha Inicio" type="date">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm6 md6>
                                                                    <v-text-field v-model="carrusel.fecha_final"
                                                                        label="Fecha Final" type="date">
                                                                    </v-text-field>
                                                                </v-flex>
                                                            </v-layout>
                                                            <v-layout>
                                                                <v-flex xs12 md12>
                                                                    <input type="file" @change="Obtenerimagen" />
                                                                </v-flex>
                                                            </v-layout>

                                                            <v-layout>
                                                                <v-flex xs8 md8>
                                                                    <v-img :src="verimagen" aspect-ratio="1.7 "
                                                                        with="200" height="200" contain>
                                                                    </v-img>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-container>
                                                    </v-form>
                                                </v-card-text>
                                                <v-toolbar>
                                                    <v-spacer></v-spacer>
                                                    <v-card-actions>
                                                        <v-btn color="red" dark @click="crearcarrusel = false">
                                                            Cancel
                                                        </v-btn>
                                                        <v-btn color="blue" dark @click="guardar()">
                                                            Guardar
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-toolbar>
                                            </v-card>
                                        </v-dialog>
                                    </v-toolbar>
                                    <v-data-table :headers="headers" :items="tablacarrusel" class="elevation-1">
                                        <template v-slot:items="props">
                                            <td class="text-left">
                                                <v-avatar color="grey lighten-4">
                                                    <img :src="'/storage/imagenesintranet/'+props.item.imagen"
                                                        alt="avatar" />
                                                </v-avatar>
                                            </td>
                                            <td class="text-xs-left">
                                                {{ props.item.nombre }}
                                            </td>
                                            <td class="text-left">
                                                <v-btn v-if="props.item.Nombre_estado == 'Activo'" color="success" round
                                                    v-model="cambioestado.Nombre_estado" @click="updates(props.item)">
                                                    {{props.item.Nombre_estado}}
                                                </v-btn>
                                                <v-btn v-if="props.item.Nombre_estado == 'Inactivo'" color="red" dark
                                                    round v-model="cambioestado.Nombre_estado"
                                                    @click="updates(props.item)">
                                                    {{props.item.Nombre_estado}}
                                                </v-btn>
                                            </td>
                                            <td class="text-left">
                                                {{props.item.fecha_inicio}}
                                            </td>
                                            <td class="text-left">
                                                {{props.item.fecha_final}}
                                            </td>
                                            <td class="text-left">
                                                {{props.item.enlace}}
                                            </td>
                                            <td class="text-left">
                                                {{props.item.created_at}}
                                            </td>
                                            <td class="text-left">
                                                <v-layout row justify-center>
                                                    <v-dialog v-model="modeleditarcarrusel" persistent
                                                        max-width="600px">
                                                        <template v-slot:activator="{on}">
                                                            <v-btn small fab color="warning" v-on="on"
                                                                @click="editarCarrusels(props.item)">
                                                                <v-icon>edit</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <v-card>
                                                            <v-toolbar color="success">
                                                                <v-card-title>
                                                                    <span class="headline">
                                                                        Editar Imagen Carrusel
                                                                    </span>
                                                                </v-card-title>
                                                            </v-toolbar>
                                                            <v-card-text class="ma-0 pa-0">
                                                                <v-container grid-list-md pt-0 pb-0>
                                                                    <v-form enctype="multipart/form-data">
                                                                        <v-container grid-list-md pb-0>
                                                                            <v-layout wrap>
                                                                                <v-flex xs6 sm6 md6>
                                                                                    <v-text-field
                                                                                        v-model="editarCarrusel.nombre"
                                                                                        label="Nombre" required>
                                                                                    </v-text-field>
                                                                                </v-flex>
                                                                                <v-flex xs6 sm6 md6>
                                                                                    <v-text-field
                                                                                        v-model="editarCarrusel.fecha_inicio"
                                                                                        label="Fecha Inicio"
                                                                                        type="date">
                                                                                    </v-text-field>
                                                                                </v-flex>
                                                                            </v-layout>
                                                                            <v-layout wrap>
                                                                                <v-flex xs12 md12>
                                                                                    <v-text-field
                                                                                        v-model="editarCarrusel.enlace"
                                                                                        label="enlace" required>
                                                                                    </v-text-field>
                                                                                </v-flex>
                                                                            </v-layout>
                                                                            <v-layout wrap>
                                                                                <v-flex xs12 md12>
                                                                                    <input type="file" accept="image/*"
                                                                                        ref="file"
                                                                                        @change="ObtenerimagenCarruselEditar" />
                                                                                </v-flex>
                                                                            </v-layout>
                                                                            <v-layout wrap>
                                                                                <v-flex xs12 md12>
                                                                                    <v-img
                                                                                        :src="verimagenCarruselEditar"
                                                                                        aspect-ratio="1.4" with="160"
                                                                                        height="160" contain>
                                                                                    </v-img>
                                                                                </v-flex>
                                                                            </v-layout>
                                                                        </v-container>
                                                                    </v-form>
                                                                </v-container>
                                                            </v-card-text>
                                                            <v-toolbar>
                                                                <v-spacer></v-spacer>
                                                                <v-card-actions>
                                                                    <v-btn color="red" dark @click="cerrarcarrusel()">
                                                                        Cancelar
                                                                    </v-btn>
                                                                    <v-btn color="blue" dark
                                                                        @click="actualizarcarrusel()">
                                                                        Guardar
                                                                    </v-btn>
                                                                </v-card-actions>
                                                            </v-toolbar>
                                                        </v-card>
                                                    </v-dialog>
                                                </v-layout>
                                            </td>
                                        </template>
                                    </v-data-table>
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-flex>
            </v-layout>
        </v-container>

        <v-container fluid pa-0>
            <v-layout v-if="radios == 'blog'">
                <v-flex>
                    <v-container fluid>
                        <v-layout>
                            <v-flex>
                                <v-card>
                                    <v-toolbar flat color="white">
                                        <v-dialog v-model="crearblog" persistent max-width="550px">
                                            <template v-slot:activator="{ on }">
                                                <v-btn round color="primary" dark class="mb-2" v-on="on">
                                                    Crear Noticia
                                                </v-btn>
                                                <v-spacer></v-spacer>
                                                <v-divider class="mx-2" inset vertical></v-divider>
                                                <v-toolbar-title>
                                                    Noticias
                                                </v-toolbar-title>
                                            </template>
                                            <v-card>
                                                <v-toolbar color="success">
                                                    <v-toolbar-title>
                                                        Nueva Noticia
                                                    </v-toolbar-title>
                                                    <v-spacer></v-spacer>
                                                </v-toolbar>
                                                <v-card-text class="pb-0">
                                                    <v-container grid-list-md pl-3 pb-0 pa-0>
                                                        <v-form enctype="multipart/form-data">
                                                            <v-layout wrap>
                                                                <v-flex xs6 sm6 md6>
                                                                    <v-text-field v-model="blog.titulo " label="Titulo"
                                                                        required>
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs6 sm6 md6>
                                                                    <v-text-field v-model="blog.fecha_inicio "
                                                                        label="Fecha Inicio" type="date">
                                                                    </v-text-field>
                                                                </v-flex>
                                                            </v-layout>
                                                            <v-layout wrap>
                                                                <v-flex xs12 sm12 md12>
                                                                    <vue-editor v-model="blog.subtexto" />
                                                                </v-flex>
                                                            </v-layout>
                                                            <v-layout wrap>
                                                                <v-flex xs12 sm12 md12>
                                                                    <vue-editor v-model="blog.texto" />
                                                                </v-flex>
                                                            </v-layout>
                                                            <v-layout>
                                                                <v-flex xs12 md12>
                                                                    <input type="file" @change="Obtenerimagenblog" />
                                                                </v-flex>
                                                            </v-layout>
                                                            <v-layout>
                                                                <v-flex xs12 md12>
                                                                    <v-img :src="verimagenblog" aspect-ratio="1.3 "
                                                                        with="150" height="150" contain>
                                                                    </v-img>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-form>
                                                    </v-container>
                                                </v-card-text>
                                                <v-toolbar>
                                                    <v-spacer></v-spacer>
                                                    <v-card-actions>
                                                        <v-btn color="red" dark @click="crearblog = false">
                                                            Cancel
                                                        </v-btn>
                                                        <v-btn color="blue" dark @click="guardarblog()">
                                                            Guardar
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-toolbar>
                                            </v-card>
                                        </v-dialog>
                                    </v-toolbar>
                                    <v-data-table :headers="itemsblog" :items="tablablog" class="elevation-1">
                                        <template v-slot:items="props">
                                            <td class="text-left">
                                                <v-avatar color="grey lighten-4">
                                                    <img :src="'/storage/imagenesintranet/'+props.item.imagen"
                                                        alt="avatar" />
                                                </v-avatar>
                                            </td>
                                            <td class="text-xs-left">{{props.item.titulo}}</td>
                                            <td v-html="props.item.subtexto" class="text-xs-left"></td>
                                            <td v-html="props.item.texto" class="text-xs-left">
                                            </td>
                                            <td class="text-left">
                                                <v-btn v-if="props.item.Nombre_estado=='Activo'" color="success" round
                                                    v-model="blogestado.Nombre_estado" @click="update(props.item)">
                                                    {{props.item.Nombre_estado}}
                                                </v-btn>
                                                <v-btn v-if="props.item.Nombre_estado == 'Inactivo'" color="red" dark
                                                    round v-model="blogestado.Nombre_estado"
                                                    @click="update(props.item)">
                                                    {{props.item.Nombre_estado}}
                                                </v-btn>
                                            </td>
                                            <td class="text-left">
                                                {{props.item.created_at}}
                                            </td>
                                            <td>
                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn icon color="warning" dark v-on="on"
                                                            @click="dialog1 = true,verdetalles(props.item.id)">
                                                            {{props.item.detalle_count}}</v-btn>
                                                    </template>
                                                    <span>Detalles</span>
                                                </v-tooltip>
                                                <v-dialog v-model="dialog1" persistent width="800">
                                                    <v-card>
                                                        <v-toolbar color="success"
                                                            class="success white--text headline justify-center">
                                                            <v-toolbar-title>
                                                                Detalle de visitas
                                                            </v-toolbar-title>
                                                            <v-spacer></v-spacer>
                                                        </v-toolbar>

                                                        <v-container>
                                                            <v-data-table :headers="usuario" :items="verusuariosdetalle"
                                                                class="elevation-1">
                                                                <template v-slot:items="props">
                                            <td>{{ props.item.nombre }}</td>
                                            <td class="">{{props.item.estado}}</td>
                                            <td>
                                                <v-btn icon color="warning">{{props.item.usuario}}</v-btn>
                                            </td>
                                        </template>
                                    </v-data-table>
                    </v-container>

                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" flat @click="dialog1 = false">
                            Cancelar
                        </v-btn>
                    </v-card-actions>
                    </v-card>
                    </v-dialog>
                    </td>
                    <td class="text-left">
                        <v-layout row justify-center>
                            <v-dialog v-model="editar" persistent max-width="600px">
                                <template v-slot:activator="{on}">
                                    <v-btn small fab color="warning" v-on="on" @click="editarblog(props.item)">
                                        <v-icon>edit</v-icon>
                                    </v-btn>
                                </template>
                                <v-card>
                                    <v-toolbar color="success">
                                        <v-card-title>
                                            <span class="headline">
                                                Editar Noticia
                                            </span>
                                        </v-card-title>
                                    </v-toolbar>
                                    <v-card-text class="ma-0 pa-0">
                                        <v-container grid-list-md pt-0 pb-0>
                                            <v-form enctype="multipart/form-data">
                                                <v-container grid-list-md pb-0>
                                                    <v-layout wrap>
                                                        <v-flex xs6 sm6 md6>
                                                            <v-text-field v-model="editblog.titulo" label="Titulo"
                                                                type="text">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs6 sm6 md6>
                                                            <v-text-field v-model="editblog.fecha_inicio"
                                                                label="Fecha Inicio" type="date">
                                                            </v-text-field>
                                                        </v-flex>
                                                    </v-layout>
                                                    <v-layout wrap>
                                                        <v-flex xs12 sm12 md12>
                                                            <vue-editor v-model="editblog.subtexto" />
                                                        </v-flex>
                                                    </v-layout>
                                                    <v-layout wrap>
                                                        <v-flex xs12 sm12 md12>
                                                            <vue-editor v-model="editblog.texto" />
                                                        </v-flex>
                                                    </v-layout>
                                                    <v-layout wrap>
                                                        <v-flex xs12 md12>
                                                            <input type="file" accept="image/*" ref="file"
                                                                @change="ObtenerimagenblogEditar" />
                                                        </v-flex>
                                                    </v-layout>
                                                    <v-layout wrap>
                                                        <v-flex xs12 md12>
                                                            <v-img :src="verimagenblogEditar" aspect-ratio="1.4"
                                                                with="160" height="160" contain>
                                                            </v-img>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-form>
                                        </v-container>
                                    </v-card-text>
                                    <v-toolbar>
                                        <v-spacer></v-spacer>
                                        <v-card-actions>
                                            <v-btn color="red" dark @click="cerrar()">
                                                Cancelar
                                            </v-btn>
                                            <v-btn color="blue" dark @click="actualizarblog()">
                                                Guardar
                                            </v-btn>
                                        </v-card-actions>
                                    </v-toolbar>
                                </v-card>
                            </v-dialog>
                        </v-layout>
                    </td>
                </template>
            </v-data-table>
        </v-card>
    </v-flex>
</v-layout>
            </v-container>
            </v-flex>
            </v-layout>
        </v-container>

        <v-container fluid pa-0>
            <v-layout v-if="radios == 'multimedia'">
                <v-flex>
                    <v-container pa-2>
                        <v-layout>
                            <v-flex>
                                <v-card>
                                    <v-toolbar flat color="white">
                                        <v-dialog v-model="crearmultimedia" persistent max-width="500px">
                                            <template v-slot:activator="{ on }">
                                                <v-btn round color="primary" dark class="mb-2" v-on="on">
                                                    Agregar Multimedia
                                                </v-btn>
                                                <v-spacer></v-spacer>
                                                <v-divider class="mx-2" inset vertical></v-divider>
                                                <v-toolbar-title>
                                                    Noticias
                                                </v-toolbar-title>
                                            </template>
                                            <v-card>
                                                <v-toolbar color="success">
                                                    <v-toolbar-title>
                                                        Nueva Multimedia
                                                    </v-toolbar-title>
                                                    <v-spacer></v-spacer>
                                                </v-toolbar>
                                                <v-card-text class="pb-0">
                                                    <v-container grid-list-md pl-3 pb-0 pa-0>
                                                        <v-form enctype="multipart/form-data">
                                                            <v-container grid-list-md>
                                                                <v-layout wrap>
                                                                    <v-flex xs12 sm12 md12>
                                                                        <v-text-field v-model="crearvideo.nombre" label="Nombre"
                                                                            required>
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                </v-layout>
                                                                <v-layout wrap>
                                                                    <v-flex xs12 sm6 md6>
                                                                        <v-text-field v-model="crearvideo.fecha_inicio"
                                                                            label="Fecha Inicio" type="date">
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12 sm6 md6>
                                                                        <v-text-field v-model="crearvideo.fecha_final"
                                                                            label="Fecha Final" type="date">
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                </v-layout>
                                                                <v-layout wrap>
                                                                    <v-flex xs12 md12>
                                                                        <v-text-field placeholder="Enter a YouTube URL"
                                                                            v-model="crearvideo.link" required
                                                                            @keypress.native.enter="loadURL()">
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                </v-layout>
                                                                <v-layout wrap>
                                                                    <v-flex xs12 md12>
                                                                        <iframe width="100" height="100" :src="resultado"
                                                                            frameborder="0"
                                                                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                                            allowfullscreen>
                                                                        </iframe>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                        </v-form>
                                                    </v-container>
                                                </v-card-text>
                                                <v-toolbar>
                                                    <v-spacer></v-spacer>
                                                    <v-card-actions>
                                                        <v-btn color="red" dark @click="crearmultimedia = false">
                                                            Cancel
                                                        </v-btn>
                                                        <v-btn color="success" dark @click="guardarvideo()">
                                                            Guardar
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-toolbar>
                                            </v-card>
                                        </v-dialog>
                                    </v-toolbar>
                                    <v-data-table :headers="itemsmultimedia" :items="multimedia" class="elevation-1">
                                        <template v-slot:items="props">
                                            <td class="text-left">
                                                <youtube width="70" height="70" :video-id="props.item.link" ref="youtube"></youtube>
                                            </td>
                                            <td class="text-xs-left">
                                                {{ props.item.nombre }}
                                            </td>
                                            <td class="text-left">
                                                <v-btn v-if="props.item.Nombre_estado == 'Activo'" dark color="success" round
                                                    v-model="videoestados.Nombre_estado" @click="estadovideo(props.item)">
                                                    {{props.item.Nombre_estado}}
                                                </v-btn>
                                                <v-btn v-if="props.item.Nombre_estado =='Inactivo'" dark color="red" round
                                                    v-model="videoestados.Nombre_estado" @click="estadovideo(props.item)">
                                                    {{props.item.Nombre_estado}}
                                                </v-btn>
                                            </td>
                                            <td class="text-left">
                                                {{props.item.created_at}}
                                            </td>
                                            <td>
                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn icon color="warning" dark v-on="on"
                                                            @click="dialog = true,veruser(props.item.id)">
                                                            {{props.item.relacion_count}}</v-btn>
                                                    </template>
                                                    <span>Detalles</span>
                                                </v-tooltip>
                                                <v-dialog v-model="dialog" persistent width="800">
                                                    <v-card>
                                                        <v-toolbar color="success"
                                                            class="success white--text headline justify-center">
                                                            <v-toolbar-title>
                                                                Detalle de visitas
                                                            </v-toolbar-title>
                                                            <v-spacer></v-spacer>
                                                        </v-toolbar>

                                                        <v-container>
                                                            <v-data-table :headers="usuario" :items="verusuarios"
                                                                class="elevation-1">
                                                                <template v-slot:items="props">
                                                                    <td>{{ props.item.nombre }}</td>
                                                                    <td class="">{{props.item.estado}}</td>
                                                                    <td>
                                                                        <v-btn icon color="warning">{{props.item.usuario}}</v-btn>
                                                                    </td>
                                                                </template>
                                                            </v-data-table>
                                                        </v-container>

                                                        <v-divider></v-divider>
                                                        <v-card-actions>
                                                            <v-spacer></v-spacer>
                                                            <v-btn color="primary" flat @click="dialog = false">
                                                                Cancelar
                                                            </v-btn>
                                                        </v-card-actions>
                                                    </v-card>
                                                </v-dialog>
                                            </td>
                                            <td class="text-left">
                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn icon color="warning" dark v-on="on" @click="videoeditar(props.item)">
                                                            <v-icon>edit</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Editar Video</span>
                                                </v-tooltip>
                                                <v-dialog v-model="dialogoeditar" persistent max-width="500px">
                                                    <v-card>
                                                        <v-toolbar color="success">
                                                            <v-toolbar-title>
                                                                Nueva Multimedia
                                                            </v-toolbar-title>
                                                            <v-spacer></v-spacer>
                                                        </v-toolbar>
                                                        <v-card-text class="pb-0">
                                                            <v-container grid-list-md pl-3 pb-0 pa-0>
                                                                <v-form enctype="multipart/form-data">
                                                                    <v-container grid-list-md>
                                                                        <v-layout wrap>
                                                                            <v-flex xs12 sm12 md12>
                                                                                <v-text-field v-model="editarvideo.nombre" label="Nombre" required>
                                                                                </v-text-field>
                                                                            </v-flex>
                                                                        </v-layout>
                                                                        <v-layout wrap>
                                                                            <v-flex xs12 sm6 md6>
                                                                                <v-text-field v-model="editarvideo.fecha_inicio" label="Fecha Inicio"
                                                                                    type="date">
                                                                                </v-text-field>
                                                                            </v-flex>
                                                                            <v-flex xs12 sm6 md6>
                                                                                <v-text-field v-model="editarvideo.fecha_final" label="Fecha Final"
                                                                                    type="date">
                                                                                </v-text-field>
                                                                            </v-flex>
                                                                        </v-layout>
                                                                        <v-layout wrap>
                                                                            <v-flex xs12 md12>
                                                                                <v-text-field placeholder="Enter a YouTube URL"
                                                                                    v-model="editarvideo.link" required
                                                                                    @keypress.native.enter="loadURLeditar()">
                                                                                </v-text-field>
                                                                            </v-flex>
                                                                        </v-layout>
                                                                        <v-layout wrap>
                                                                            <v-flex xs12 md12>
                                                                                <iframe width="100" height="100" :src="resultadoeditar" frameborder="0"
                                                                                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                                                    allowfullscreen>
                                                                                </iframe>
                                                                            </v-flex>
                                                                        </v-layout>
                                                                    </v-container>
                                                                </v-form>
                                                            </v-container>
                                                        </v-card-text>
                                                        <v-toolbar>
                                                            <v-spacer></v-spacer>
                                                            <v-card-actions>
                                                                <v-btn color="red" dark @click="dialogoeditar = false">
                                                                    Cancel
                                                                </v-btn>
                                                                <v-btn color="success" dark @click="editarvideos()">
                                                                    Guardar
                                                                </v-btn>
                                                            </v-card-actions>
                                                        </v-toolbar>
                                                    </v-card>
                                                </v-dialog>

                                            </td>
                                        </template>
                                    </v-data-table>
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-flex>
            </v-layout>
        </v-container>
    </v-container>
</template>

<script>
    import Swal from "sweetalert2";
    import {
        VueEditor
    } from "vue2-editor";
    import {
        mapGetters
    } from "vuex";
    export default {
        name: 'adminsumintranet',
        data() {
            return {
                dialogoeditar: false,
                dialog: false,
                dialog1: false,
                date: null,
                menus: false,
                menu: false,
                radios: '',
                crearcarrusel: false,
                modal: false,
                modals: false,
                minfechafinal: new Date().toISOString().substr(0, 10),
                crearcarrusel: false,
                carrusel: {
                    nombre: "",
                    fecha_inicio: "",
                    fecha_final: "",
                    imagen: "",
                    estado_id: "",
                    enlace: ""
                },
                cambioestado: {},
                imagenminiatura: "",
                tablacarrusel: [],
                modeleditarcarrusel: false,
                editarCarrusel: {
                    nombre: "",
                    fecha_inicio: "",
                    fecha_final: "",
                    imagen: "",
                    enlace: ""
                },
                headers: [{
                        text: "imagen",
                        sortable: false,
                    },
                    {
                        text: "Nombre",
                        sortable: false,
                    },
                    {
                        text: "estado",
                        sortable: false,
                    },
                    {
                        text: "fecha inicio",
                        sortable: false,
                    },
                    {
                        text: "fecha final",
                        sortable: false,
                    },
                    {
                        text: "Enlace",
                        sortable: false,
                    },
                    {
                        text: "fecha creacion",
                        sortable: false,
                    },
                    {
                        text: "Editar",
                        sortable: false,
                    }
                ],
                modal1: false,
                modal2: false,
                crearblog: false,
                modalnoticia3: false,
                modalfechainicio: false,
                blog: {
                    subtexto: 'subtexto',
                    titulo: "",
                    texto: 'texto',
                    fecha_inicio: "",
                    fecha_final: "",
                    imagen: "",
                    estado_id: ""
                },
                editblog: {
                    titulo: "",
                    texto: "",
                    fecha_inicio: "",
                    fecha_final: "",
                    imagen: "",
                },
                tablablog: [],
                imagenminiaturablog: "",
                imagenminiaturablogEditar: "",
                imagenminiaturaCarruselEditar: "",
                blogestado: {},
                itemsblog: [{
                        text: "imagen",
                        sortable: false,
                    },
                    {
                        text: "titulo",
                        sortable: false,
                    },
                    {
                        text: "subtexto",
                        sortable: false,
                    },
                    {
                        text: "texto",
                        sortable: false,
                    },
                    {
                        text: "estado",
                        sortable: false,
                    },
                    {
                        text: "fecha creacion",
                        sortable: false,
                    },
                    {
                        text: "Detalles",
                        sortable: false,
                    },
                    {
                        text: "Editar",
                        sortable: false,
                    }
                ],
                editar: false,
                modal3: false,
                modal4: false,
                resultado: "",
                crearmultimedia: false,
                multimedia: [],
                video: [],
                crearvideo: {
                    nombre: "",
                    link: "",
                    estado_id: "",
                    fecha_inicio: new Date().toISOString().substr(0, 10),
                    fecha_final: ""
                },
                editarvideo: {},
                videoestados: {},
                itemsmultimedia: [{
                        text: "link",
                        sortable: false,
                    },
                    {
                        text: "nombre",
                        sortable: false,
                    },
                    {
                        text: "estado",
                        sortable: false,
                    },
                    {
                        text: "fecha creacion",
                        sortable: false,
                    },
                    {
                        text: "Detalles",
                        sortable: false,
                    },
                    {
                        text: "Editar",
                        sortable: false,
                    }
                ],
                usuario: [{
                        text: "Nombre",
                        sortable: false,
                    },
                    {
                        text: "Estado",
                        sortable: false,
                    },
                    {
                        text: "Numero de Vistas",
                        sortable: false,
                    }
                ],
                estado: [{
                        idestado: 1,
                        Nombre: "activo"
                    },
                    {
                        idestado: 2,
                        Nombre: "inactivo"
                    }
                ],
                verusuarios: [],
                verusuariosdetalle: [],
                resultadoeditar: ""
            }
        },
        mounted() {
            /* CARRUSEL */
            this.informacionCarrusel();
            this.informaciontablacarrusel();
            /* BLOG / NOTICIAS */
            this.informacionblog();
            this.informaciontablablog();
            /* Multimedia */
            this.multimedias();
            this.tablamultimedia();
        },
        methods: {
            informacionCarrusel() {
                axios.get("api/intranet/carrusel").then(res => {
                    this.items = res.data;
                });
            },
            informaciontablacarrusel() {
                axios.get("api/intranet/index").then(res => {
                    this.tablacarrusel = res.data;
                });
            },
            Obtenerimagen(e) {
                let file = e.target.files[0];
                this.carrusel.imagen = file;
                this.cargarimagen(file);
            },
            cargarimagen(file) {
                let reader = new FileReader();
                reader.onload = e => {
                    this.imagenminiatura = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            saves() {
                this.$refs.menu.save()
            },
            guardar() {
                let formData = new FormData();
                formData.append("nombre", this.carrusel.nombre);
                formData.append("imagen", this.carrusel.imagen);
                formData.append("enlace", this.carrusel.enlace);
                formData.append("estado_id", this.carrusel.estado_id);
                formData.append("fecha_inicio", this.carrusel.fecha_inicio);
                formData.append("fecha_final", this.carrusel.fecha_final);

                axios.post("api/intranet/create", formData).then(res => {
                        swal({
                            title: "CREADO CON EXITO!",
                            text: "  ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        this.crearcarrusel = false;
                        this.informaciontablacarrusel();
                        this.informacionCarrusel(); //este va en el home
                        this.limpiarCampos();
                    })
                    .catch(e => {
                        let errors = e.response.data.errors;
                        let mensaje = "ERROR NO ENCANTRADO!";

                        if (errors.hasOwnProperty("nombre")) {
                            mensaje = errors.nombre[0];
                        } else if (errors.hasOwnProperty("estado_id")) {
                            mensaje = errors.estado_id[0];
                        } else if (errors.hasOwnProperty("fecha_final")) {
                            mensaje = errors.fecha_final[0];
                        } else if (errors.hasOwnProperty("imagen")) {
                            mensaje = errors.imagen[0];
                        }
                        swal({
                            title: "¡Error!",
                            text: mensaje,
                            type: "error",
                            timer: 3000,
                            icon: "error",
                            buttons: false
                        });
                    });
            },
            updates(id) {
                this.cambioestado.Nombre_estado = id.idestado;
                this.cambioestado.id = id.id;

                Swal.fire({
                    title: "¿Está Seguro(a)?",
                    text: "Se cancela la Solicitud!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Guardar"
                }).then(result => {
                    if (result.isConfirmed) {
                        axios
                            .put(
                                `/api/intranet/estado/${this.cambioestado.id}`,
                                this.cambioestado
                            )
                            .then(res => {
                                this.informaciontablacarrusel();
                                this.informacionCarrusel();
                            });
                        Swal.fire(" ", "El estado se modifico con exito.", "success");
                    }
                });
            },
            onPickFile() {
                this.$refs.fileInput.click();
            },
            limpiarCampos() {
                this.carrusel.nombre = "";
                this.carrusel.imagen = "";
                this.carrusel.estado_id = "";
                this.carrusel.fecha_final = "";
                this.carrusel.fecha_inicio = "";
            },
            ObtenerimagenCarruselEditar(e) {
                let file = e.target.files[0];
                this.editarCarrusel.imagen = file;
                this.cargarimagencarruseleditar(file);
            },
            cargarimagencarruseleditar(file) {
                let reader = new FileReader();
                reader.onload = e => {
                    this.imagenminiaturaCarruselEditar = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            editarCarrusels(item) {
                this.editarCarrusel.id = item.id;
                this.editarCarrusel.enlace = item.enlace;
                this.editarCarrusel.nombre = item.nombre;
                this.editarCarrusel.fecha_inicio = item.fecha_inicio;
                this.editarCarrusel.imagen = item.imagen;
            },
            actualizarcarrusel() {
                let data = new FormData();
                data.append('id', this.editarCarrusel.id);
                data.append('nombre', this.editarCarrusel.nombre);
                data.append('enlace', this.editarCarrusel.enlace);
                data.append('fecha_inicio', this.editarCarrusel.fecha_inicio);
                data.append('imagen', this.editarCarrusel.imagen);

                axios.post('/api/intranet/updatecarrusel', data)
                    .then(res => {
                        swal({
                            title: "ACTUALIZADO CON EXITO!",
                            text: "  ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        this.modeleditarcarrusel = false;
                        this.informaciontablacarrusel();
                        this.informacionCarrusel();
                    });
            },
            cerrarcarrusel() {
                this.modeleditarcarrusel = false;
            },
            /* BLOG */
            save() {
                this.$refs.menus.save()
            },
            informacionblog() {
                axios.get("api/intranet/blogs").then(res => {
                    this.cards = res.data;
                });
            },
            informaciontablablog() {
                axios.get("api/intranet/blog").then(res => {
                    this.tablablog = res.data;
                });
            },
            Obtenerimagenblog(e) {
                let file = e.target.files[0];
                this.blog.imagen = file;
                this.cargarimagenblog(file);
            },
            cargarimagenblog(file) {
                let reader = new FileReader();
                reader.onload = e => {
                    this.imagenminiaturablog = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            guardarblog() {
                let formData = new FormData();
                formData.append("subtexto", this.blog.subtexto);
                formData.append("titulo", this.blog.titulo);
                formData.append("texto", this.blog.texto);
                formData.append("fecha_inicio", this.blog.fecha_inicio);
                // formData.append("fecha_final", this.blog.fecha_final);
                formData.append("imagen", this.blog.imagen);
                formData.append("estado_id", this.blog.estado_id);

                axios.post("api/intranet/crear", formData).then(res => {
                    this.crearblog = false;
                    this.informaciontablablog();
                    this.informacionblog();
                    this.limpiarCamposblog();
                }).catch(e => {
                    let errors = e.response.data.errors;
                    let mensaje = "ERROR NO ENCANTRADO!";

                    if (errors.hasOwnProperty("subtexto")) {
                        mensaje = errors.subtexto[0];
                    } else if (errors.hasOwnProperty("titulo")) {
                        mensaje = errors.titulo[0];
                    } else if (errors.hasOwnProperty("fecha_inicio")) {
                        mensaje = errors.fecha_inicio[0];
                    } else if (errors.hasOwnProperty("texto")) {
                        mensaje = errors.texto[0];
                    }
                    swal({
                        title: "¡Error!",
                        text: mensaje,
                        type: "error",
                        timer: 3000,
                        icon: "error",
                        buttons: false
                    });
                });;
            },
            update(id) {
                this.blogestado.Nombre_estado = id.idestado;
                this.blogestado.id = id.id;

                Swal.fire({
                    title: "¿Está Seguro(a)?",
                    text: "Se cancela la Solicitud!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Guardar"
                }).then(result => {
                    if (result.isConfirmed) {
                        axios
                            .put(`/api/intranet/cambiar/${this.blogestado.id}`, this.blogestado)
                            .then(res => {
                                this.informaciontablablog();
                                this.informacionblog();
                            });
                        Swal.fire(" ", "El estado se modifico con exito.", "success");
                    }
                });
            },
            ObtenerimagenblogEditar(e) {
                let file = e.target.files[0];
                this.editblog.imagen = file;
                this.cargarimagenblogEditar(file);
                // this.editarblog(file);
            },
            cargarimagenblogEditar(file) {
                let reader = new FileReader();
                reader.onload = e => {
                    this.imagenminiaturablogEditar = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            editarblog(item) {
                this.editblog.id = item.id;
                this.editblog.subtexto = item.subtexto;
                this.editblog.titulo = item.titulo;
                this.editblog.texto = item.texto;
                this.editblog.fecha_inicio = item.fecha_inicio;
                this.editblog.imagen = item.imagen;
                // this.editar = true
            },
            actualizarblog() {
                let data = new FormData();
                data.append('id', this.editblog.id);
                data.append('imagen', this.editblog.imagen);
                data.append('titulo', this.editblog.titulo);
                data.append('texto', this.editblog.texto);
                data.append('subtexto', this.editblog.subtexto);
                data.append('fecha_inicio', this.editblog.fecha_inicio);

                axios.post('/api/intranet/actualizar', data)
                    .then(res => {
                        swal({
                            title: "ACTUALIZADO CON EXITO!",
                            text: "  ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        this.editar = false;
                        this.informaciontablablog();
                        this.informacionblog();
                    });
            },
            cerrar() {
                this.editar = false;
            },
            limpiarCamposblog() {
                (this.blog.subtexto = ""),
                (this.blog.titulo = ""),
                (this.blog.texto = ""),
                (this.blog.imagen = ""),
                (this.blog.fecha_inicio = ""),
                (this.blog.fecha_final = ""),
                (this.blog.estado_id = ""),
                (this.imagenminiaturablog = "");
            },
            verdetalles(e) {
                axios.get(`api/intranet/verusuario/${e}`).then(res => {
                    this.verusuariosdetalle = res.data;
                });
            },
            /* Multimedia */
            multimedias() {
                axios.get("api/intranet/multimedia").then(res => {
                    this.video = res.data;
                });
            },
            tablamultimedia() {
                axios.get("api/intranet/tabla").then(res => {
                    this.multimedia = res.data;
                });
            },
            guardarvideo() {
                axios.post("api/intranet/video", this.crearvideo).then(res => {
                    this.crearmultimedia = false;
                    this.tablamultimedia();
                    this.multimedias();
                    this.limpiarCamposvideo();
                }).catch(e => {
                    let errors = e.response.data.errors;
                    let mensaje = "ERROR NO ENCANTRADO!";

                    if (errors.hasOwnProperty("nombre")) {
                        mensaje = errors.nombre[0];
                    } else if (errors.hasOwnProperty("link")) {
                        mensaje = errors.link[0];
                    } else if (errors.hasOwnProperty("fecha_final")) {
                        mensaje = errors.fecha_final[0];
                    } else if (errors.hasOwnProperty("fecha_final")) {
                        mensaje = errors.fecha_final[0];
                    }
                    swal({
                        title: "¡Error!",
                        text: mensaje,
                        type: "error",
                        timer: 3000,
                        icon: "error",
                        buttons: false
                    });
                });;
            },
            estadovideo(id) {
                this.videoestados.Nombre_estado = id.idestado;
                this.videoestados.id = id.id;

                Swal.fire({
                    title: "¿Está Seguro(a)?",
                    text: "Se cancela la Solicitud!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Guardar"
                }).then(result => {
                    if (result.isConfirmed) {
                        axios
                            .put(
                                `/api/intranet/estadovideo/${this.videoestados.id}`,
                                this.videoestados
                            )
                            .then(res => {
                                this.tablamultimedia();
                                this.multimedias();
                            });
                        Swal.fire(" ", "El estado se modifico con exito.", "success");
                    }
                });
            },
            limpiarCamposvideo() {
                this.crearvideo.nombre = "";
                this.crearvideo.link = "";
                this.crearvideo.estado_id = "";
            },
            veruser(e) {
                axios.get(`api/intranet/verusuarios/${e}`).then(res => {
                    this.verusuarios = res.data;
                });
            },
            loadURL() {
                const youtubeEmbedTemplate = 'https://www.youtube.com/embed/'
                const url = this.crearvideo.link.split(/(vi\/|v%3D|v=|\/v\/|youtu\.be\/|\/embed\/)/)

                const YId = undefined !== url[2] ? url[2].split(/[^0-9a-z_/\\-]/i)[0] : url[0]
                console.log("YId", YId)
                if (YId === url[0]) {
                    // console.log("not a youtube link")
                } else {
                    // console.log("it's  a youtube video")
                }
                const topOfQueue = youtubeEmbedTemplate.concat(YId)
                this.resultado = topOfQueue
                //   this.crearvideo.link = ''
            },
            loadURLeditar() {
                const youtubeEmbedTemplate = 'https://www.youtube.com/embed/'
                const url = this.editarvideo.link.split(/(vi\/|v%3D|v=|\/v\/|youtu\.be\/|\/embed\/)/)

                const YId = undefined !== url[2] ? url[2].split(/[^0-9a-z_/\\-]/i)[0] : url[0]
                console.log("YId", YId)
                if (YId === url[0]) {
                    // console.log("not a youtube link")
                } else {
                    // console.log("it's  a youtube video")
                }
                const topOfQueue = youtubeEmbedTemplate.concat(YId)
                this.resultadoeditar = topOfQueue
                //   this.crearvideo.link = ''
            },
            videoeditar(item) {
                this.dialogoeditar = true;
                this.editarvideo.fecha_final = item.fecha_final,
                    this.editarvideo.fecha_inicio = item.fecha_inicio,
                    this.editarvideo.link = item.link,
                    this.editarvideo.nombre = item.nombre
                this.editarvideo.id = item.id
            },
            editarvideos() {
                axios.put(`/api/intranet/actualizarvideo/${this.editarvideo.id}`, this.editarvideo)
                    .then(res => {
                        swal({
                            title: "ACTUALIZADO CON EXITO!",
                            text: "  ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        this.dialogoeditar = false;
                        this.tablamultimedia();
                        this.multimedias();
                    });
            }
        },
        computed: {
            verimagenblog() {
                return this.imagenminiaturablog;
            },
            verimagenblogEditar() {
                return this.imagenminiaturablogEditar;
            },
            verimagen() {
                return this.imagenminiatura;
            },
            verimagenCarruselEditar() {
                return this.imagenminiaturaCarruselEditar;
            },
            ...mapGetters(['can']),
        },
        components: {
            VueEditor
        }
    }

</script>
<style lang="css">
    @import "~vue2-editor/dist/vue2-editor.css";

    /* Import the Quill styles you want */
    @import '~quill/dist/quill.core.css';
    @import '~quill/dist/quill.bubble.css';
    @import '~quill/dist/quill.snow.css';

</style>
