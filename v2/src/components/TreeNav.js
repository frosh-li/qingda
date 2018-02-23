import React from 'react';
import Tree, { TreeNode } from 'rc-tree';
import TreeService from '../services/tree-service.js';

export default class TreeNav extends React.Component {
    constructor(props) {
        super(props);
        this.state = ({
            treeData: [],
            selectedKeys: {checked: []},
        });
    }
    componentWillMount() {
        TreeService.getTreeNodes()
            .then(data => {
                console.log('tree nodes', data);
                this.setState({
                    treeData: data.list
                });
                setTimeout(()=>{
                    this.updateSelectedKeys();
                },0);
            }).catch(e => {
                console.log(e);
            })
    }

    updateSelectedKeys() {
        let keys = [];
        this.state.treeData.map(item => {
            keys.push(item.id);
        });
        this.setState({
            selectedKeys: {
                checked: keys
            }
        })
    }

    renderTreeNodes(pid) {
        // let node = <TreeNode title="A" key="01"></TreeNode>;
        let html = [];
        // console.log('render children', pid);
        this.state.treeData.map((item) => {
            if(item.pid == pid){
                let children = this.renderTreeNodes(item.id);

                if(item.leveltype == 4){
                    html.push(
                        <TreeNode defaultExpandAll={true} title={item.title} key={item.id} isLeaf={true}>
                            {children}
                        </TreeNode>
                    )
                }else{
                    html.push(
                        <TreeNode defaultExpandAll={true} title={item.title} key={item.id}>
                            {children}
                        </TreeNode>
                    )
                }

            }
        });
        return html;
    }

    render() {
        return (
            <Tree
                defaultExpandAll={true}
                checkable={true}
                multiple={true}
                showIcon={true}
                autoExpandParent={false}
                checkedKeys={this.state.selectedKeys}
                expandedKeys={this.state.selectedKeys.checked}
                >
                <TreeNode defaultExpandAll={true} title="全国" key="1">
                    {this.renderTreeNodes(1)}
                </TreeNode>
            </Tree>
        )
    }
}
