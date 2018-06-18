<?php
/**
 * Created by PhpStorm.
 * User: rs
 * Date: 09/10/2017
 * Time: 15:07
 */
?>
<li class="treeview">
    <a href="#">
        <i class="fa fa-exchange"></i>
        <span class="title">DW Sync</span>
        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">
        @can('dwsync_create_project', 'dwsync_sync_data')
            <li class="{{ Request::is('*dwEntityTypes*') ? 'active active-sub' : '' }}">
                <a href="{!! route('dwsync.dwEntityTypes.index') !!}"><i
                            class="fa fa-list-ul"></i><span>Dw Entity Types</span></a>
            </li>

            <li class="{{ Request::is('*dwProjects*') ? 'active active-sub' : '' }}" >
                <a href="{!! route('dwsync.dwProjects.index') !!}"><i class="fa fa-list-ol"></i><span>Dw Projects</span></a>
            </li>
            <li class="{{ Request::is('*dwQuestions*') ? 'active active-sub' : '' }}">
                <a href="{!! route('dwsync.dwQuestions.index') !!}"><i
                            class="fa fa-list-alt"></i><span>Dw Questions</span></a>
            </li>
        @endcan
        @can('dwsync_see_data')
            <li class="treeview {{ Request::is('*dwSubmission*') ? 'active active-sub' : '' }}">
                <a href="#">
                    <i class="fa fa-table"></i>
                    All Dw data
                    <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                    @include('partials.dynamic_menu')
                </ul>
            </li><!-- /.third level-->
        @endcan
        @can('dwsync_create_project')
            <li class="treeview {{ Request::is('*dwsync/mapping*') ? 'active active-sub' : '' }}">
                <a href="#">
                    <i class="fa fa-link"></i>
                    <span class="title">Idnr Mapping</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                    <li class="{{ Request::is('*mappingProjects*') ? 'active active-sub' : '' }}">
                        <a href="{!! route('dwsync.mappingProjects.index') !!}"><i class="fa fa-edit"></i><span>Mapping Projects</span></a>
                    </li>

                    <li class="{{ Request::is('*mappingQuestions*') ? 'active active-sub' : '' }}">
                        <a href="{!! route('dwsync.mappingQuestions.index') !!}"><i class="fa fa-edit"></i><span>Mapping Questions</span></a>
                    </li>
                </ul>
            </li><!-- /.third level-->
        @endcan
    </ul>
</li><!-- /.second level-->