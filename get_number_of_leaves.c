#include <stdio.h>
#include <stdlib.h>
/* ******************************************
*		Nostalgie Patrice 2016-05-16		*
*********************************************/
 
/* A binary tree with 2 pointers, pointer to left child 
   and a pointer to right child */
struct node 
{
    int data;
    struct node* left;
    struct node* right;
};
 
/* Function to get the count of leaf nodes in a binary tree*/
unsigned int getLeafCount(struct node* node)
{
  if(node == NULL)       
    return 0;
  if(node->left == NULL && node->right==NULL)      
    return 1;            
  else
    return getLeafCount(node->left)+
           getLeafCount(node->right);      
}
// function to get the maximum depth of the tree
unsigned int getDepth(struct node * node){
	int depth=0,rightDepth=0,leftDepth=0;
	if(node==NULL) return 0;
	leftDepth= getDepth(node->left);
	rightDepth= getDepth(node->right);
	if(leftDepth<=rightDepth){
		depth= rightDepth+1;
	}else if(leftDepth>rightDepth){
		depth=leftDepth+1;
	}
	return depth;
} 
 
struct node* newNode(int data) 
{
  struct node* node = (struct node*)
                       malloc(sizeof(struct node));
  node->data = data;
  node->left = NULL;
  node->right = NULL;
   
  return(node);
}
 
int main()
{
  /*create a tree*/ 
  struct node *root = newNode(1);
  root->left        = newNode(2);
  root->right       = newNode(3);
  root->left->left  = newNode(4);
  root->left->right = newNode(5); 
  root->left->right->left = newNode(6);  
  root->left->right->left->left = newNode(7); 
   

  printf("Leaf count of the tree is %d\n", getLeafCount(root));
  printf("The max depth of the tree is %d\n",getDepth(root));
   
  getchar();
  return 0;
}
